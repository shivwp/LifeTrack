<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\VendorSetting;
use App\Models\PageMeta;
use App\Models\User;
use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendNotification($title, $massage, $user_id = '', $user_ids = [])
    {
        # code...
        // $firebaseToken = DB::table('user_device_token')
        //     ->where('user_id', '=', $user_id)
        //     ->distinct('device_token')
        //     ->pluck('device_token')
        //     ->all();

        // if (count($firebaseToken) > 0) {
            
            $SERVER_API_KEY = 'AAAAmj-qviM:APA91bHtJzfYvBxVcP5fHFtJlrL-5V15j-NJBHFCoBpdSOI6Ta4gx5vk4kI5-DQIjogSXN8tRGeOo-kV_Nwrq3K9LnZrxp_Kl5_f196OvVGBm8q6vtniA-86BEkolFZK1VI_GGi54u_k';

            $data = [];
            if($user_id == ''){
                $registration_ids = DB::table('users')
                ->distinct('token')
                ->pluck('token')
                ->all();
                // $registration_ids = $firebaseToken;
            } else {
                $user = User::where('id', $user_id)->first();
                $registration_ids[] = ($user->token)??'';
            }

            if(isset($user_ids) && count($user_ids)>0){
                $registration_ids = User::whereIn('id', $user_ids)->pluck('token')->all();
                // $registration_ids = $user_ids;
            }

            $data = [
                "notification" => [
                    "title" => $title,
                    "body" => $massage,
                ],
                "data" => [
                    "type" => ($type)??'',
                ],
                "registration_ids" => $registration_ids
            ];
            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);

            return $response;
        // }
    }

    public function getVendorMeta($id, $name="", $status = false)
    {
        if (empty($name)) {
            // 
            $vendor_data = VendorSetting::where('vendor_id', $id)->select('name', 'value')
                ->pluck('value', 'name')
                ->toArray();
            return $vendor_data;
        }
        else {
            //
            if ($status) {
                // 
                $vendor_data = VendorSetting::where('vendor_id', $id)->where('name', $name)->first();
                if (!empty($vendor_data))
                    return $vendor_data->value;
                else
                    return "";
            }
            else {
                $vendor_data = VendorSetting::where('vendor_id', $id)->where('name', $name)->select('name', 'value')
                    ->pluck('value', 'name')
                    ->toArray();
                return $vendor_data;
            }
        }
    }
    public function getPageMeta($id, $key="")
    {
        if (empty($key)) {
            // 
            $PageMeta = PageMeta::where('page_id', $id)->select('key', 'value')
                ->pluck('value', 'key')
                ->toArray();
            return $PageMeta;
        }
        else {
            //
            if ($status) {
                // 
                $PageMeta = PageMeta::where('page_id', $id)->where('key', $key)->first();
                if (!empty($PageMeta))
                    return $PageMeta->value;
                else
                    return "";
            }
            else {
                $PageMeta = PageMeta::where('page_id', $id)->where('key', $key)->select('key', 'value')
                    ->pluck('value', 'key')
                    ->toArray();
                return $PageMeta;
            }
        }
    }
   
     
}
