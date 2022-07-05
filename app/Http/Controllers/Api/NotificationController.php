<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Validator;
use Auth;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function notifications(Request $request){

   
        $validator = Validator::make($request->all(), [
            'title'=>'required|string',
            'description'=>'required|string',
          ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        } 
        $now = Carbon::now()->format('Y-m-d');
        $user_id=Auth::user()->id;

        $notice=new Notification;
        $notice->user_id=$user_id;
        $notice->title=$request->title;
        $notice->description=$request->description;
        $notice->notice_date=$now;
        $notice->save();
        
        return response()->json(['status' => true, 'message' =>"Notification  Added",  ], 200);

    }

       
    public function getnotice(){
         
          $user_id=Auth::user()->id;
         	$show =Notification::where('user_id',$user_id)->orderBy('id', 'DESC')->get();
         	foreach ($show as $key => $notice) {
         		$data[]=[
                      $notice->user_id,
                      $notice->title,
                      $notice->description,
                      $notice->notice_date,
                      
         		];
         	}
            return response()->json(['status' => true, 'message' =>"Show Notification", 'data'=>$show ], 200);
        }
    }

