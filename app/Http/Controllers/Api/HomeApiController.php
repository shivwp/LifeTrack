<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserPrivacySettings;
use Carbon\Carbon;
use Validator;
use Auth;

class HomeApiController extends Controller
{

    // show Privacy Settings
    public function showPrivacySettings(Type $var = null)
    {
        # code...
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        }
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'login token error!', 'data' => []]);
        }
        $settings = UserPrivacySettings::where('user_id', $user->id)->first();
        
        $privacy_setting = [
            'profile_photo' => (isset($settings->profile_photo)&& $settings->profile_photo)?true:false,
            'mood_update' => (isset($settings->mood_update)&& $settings->mood_update)?true:false,
            'charts' => (isset($settings->charts)&& $settings->charts)?true:false,
            'activity' => (isset($settings->activity)&& $settings->activity)?true:false,
            'group_access' => (isset($settings->group_access)&& $settings->group_access)?true:false,
        ]; 

        $data_name = [
            'profile_photo' => 'Access of Profile Photo',
            'mood_update' => "User's Mood",
            'charts' => 'Charts/Groups',
            'activity' => 'App Activity',
            'group_access' => 'Group Access',
        ]; 
        $data = [];
        foreach ($privacy_setting as $key => $value) {
            # code...
            $data[] = [
                'name' => $data_name[$key],
                'key' => $key,
                'status' => $value
            ];
        }

        return response()->json(['status' => true, 'message' => "Privacy Settings", 'data' => $data], 200);
    }

    public function updatePrivacySettings(Request $request)
    {
        # code...
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        }
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'login token error!', 'data' => []]);
        }

        $parameters = $request->all();
        extract($parameters);

        // $privacy_setting = [
        //     'profile_photo' => (isset($profile_photo) && $profile_photo)?true:false,
        //     'mood_update' => (isset($mood_update) && $mood_update)?true:false,
        //     'charts' => (isset($charts) && $charts)?true:false,
        //     'budget_actual' => (isset($budget_actual) && $budget_actual)?true:false,
        //     'chart_name' => (isset($chart_name) && $chart_name)?true:false,
        // ];
        
        $privacy_setting = UserPrivacySettings::where('user_id', $user->id)->first();
        if(!$privacy_setting) {
            $privacy_setting = new UserPrivacySettings;
            $privacy_setting->user_id = $user->id;
        }
        if(isset($profile_photo)) {
            $privacy_setting->profile_photo = (isset($profile_photo) && $profile_photo == 'true')?true:false;
        }
        if(isset($mood_update)) {
            $privacy_setting->mood_update = (isset($mood_update) && $mood_update == 'true')?true:false;
        }
        if(isset($charts)) {
            $privacy_setting->charts = (isset($charts) && $charts == 'true')?true:false;
        }
        if(isset($activity)) {
            $privacy_setting->activity = (isset($activity) && $activity == 'true')?true:false;
        }
        if(isset($group_access)) {
            $privacy_setting->group_access = (isset($group_access) && $group_access == 'true')?true:false;
        }
        $privacy_setting->save();
        
        return response()->json(['status' => true, 'message' => "Privacy Settings Updated"], 200);
    }

    public function sendFcmNotification(Request $request)
    {
        # code...
        $response = $this->sendNotification($request->title, $request->massage, $request->key, []);
        return response()->json(['status' => true, 'message' => json_decode($response)], 200);
    }

    public function cronSendFcmNotification(Request $request)
    {
        # code...
        $title = 'Set your daily activity!'; 
        $massage = 'Set your daily activity target to archive the target!';
        $this->sendNotification($title, $massage, '', []);
    }
}
