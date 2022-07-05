<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\subscriptions;
use App\Models\User;
use App\Models\UserCard;
use App\Models\Planpayment;
use App\Helper;
use Validator;
use Auth;
use DB;



class SubscriptionController extends Controller
{
    public function plan(Request $request) {
        $data = subscriptions::all();

        return response()->json(['status' => 'success', 'message' => "Plan Details", 'data' =>$data ], 200);
    }
   
    public function subscriptionPayment(Request $request) {

               try {

            if (Auth::guard('api')->check()) {
                $user = Auth::guard('api')->user();
            }
            if(!$user) {
                return response()->json(['status' => 'fail', 'message' => 'login token error!']);
            }

            $validator = Validator::make($request->all(), [
                'subscription_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = $validator->errors();
                return response()->json(['status' => 'success', 'message' => implode("", $validator->errors()->all()) ], 200);
            }

            $parameters = $request->all();
            extract($parameters);
            
            $subscriptions = subscriptions::where('id', $request->subscription_id)->first();
            if(!$subscriptions){

              return response()->json(['status' => 'false', 'message' => 'Your subscriptions Does Not Matched']);
            }
            require_once(base_path('vendor/stripe/stripe-php').'/init.php');
        
       
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

            
            $customer_id = '';
            
            if($user->customer_id == "") {
                // 
                $customer = $stripe->customers->create([
                    'email' => $user->email,
                    'name' => $user->first_name,
                    'phone' => ($user->phone != '') ? $user->phone : '',
                    'description' => 'customer_'.$user->id,
                      //"source" => $src_token, 
                ]);  // -- done

                $customer_id = $customer->id;

                User::where('id', $user->id)->update([
                    'customer_id' => $customer_id,
                    'subs_status'=> '1',
                ]);

                } 
               else {
                
               if($customer_id = $user->customer_id)
               {
                return response()->json(['status' => 'false', 'message' => 'Your Have Taken All Ready Subscriptions']);
               }
                }

            $tok = $stripe->tokens->create([
                'card' => [
                    'number' => '4242424242424242',
                    'exp_month' => 11,
                    'exp_year' => 2022,
                    'cvc' => '314',
                ],
            ]);
            

           if($isCardNew) {
                // 
                $card_token = '';

                try {
                    $cardinfo = $stripe->customers->createSource(
                        $customer_id,
                        ['source' => $tok->id]
                       // ['source' => $src_token]
                    );  //-- done

                    $card_token = $cardinfo->id;

                } 
            
                catch (\Stripe\Exception\InvalidRequestException $e) {
                    return response()->json(['status' => 'fail', 'message' => $e->getError()->message], 200);
                }
              
                $new_card = UserCard::insert([
                    'user_id' => $user->id, 
                    'user_customer_id' => $customer_id,
                    'card_token' => $card_token,
                ]);


            } else {
                // 
                
                $card_token = $src_token;
            }

            try {
                // 
                $paymentIntent = $stripe->paymentIntents->create([
                    'amount' => $subscriptions->price * 100,
                    'currency' => 'usd',
                    'payment_method_types' => ['card'],
                    'customer' => $customer_id,
                    'payment_method' => $card_token, // 'card_1Jht6ZEUI2VlKHRnc5KrHBMF',
                    'transfer_group' => $subscriptions->id,
                    'confirm'=>'true',
                ]);

                

				

            } catch (\Stripe\Exception\InvalidRequestException $e) {
                // 
                // Invalid parameters were supplied to Stripe's API
                return response()->json(['status' => 'fail', 'message' => $e->getError()->message], 200);
            }

            

            if($paymentIntent->status == 'succeeded') {
                // 
                DB::table('plan_payments')->insert([
                    'user_id' => $user->id, 
                    'subscription_id' => $subscription_id,
                    'status' => $paymentIntent->status,
                    'payment_id' => $paymentIntent->id,
                    'amount' => $subscriptions->price,
                    'trans_id' => $paymentIntent->id,
                    'balance_transaction' => $paymentIntent->charges->data[0]->balance_transaction,
                    'charge_id' => $paymentIntent->charges->data[0]->id,
                ]);

               
                
                
                return response()->json(['status' => 'success', 'message' => "Payment Successfull", 'user_info' => $user, 'data'=>$paymentIntent ], 200);

            } else {
                // 
                return response()->json(['status' => 'fail', 'message' => "Payment fail", 'data' =>[] ], 200);
            }

        } catch(Exception $e) {
            $response['status'] = "fail";
            $response['message'] = "Error: ".$e;
            return response()->json($response); 
        }
    }

}
