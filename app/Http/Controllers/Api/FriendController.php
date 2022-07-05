<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Friend;
use App\Models\User;
use App\Models\Fillings;
use App\Models\Page;
use Validator;
use Auth;
use App\Models\Tag;
use Carbon\Carbon;
use App\Models\UserPrivacySettings;

class FriendController extends Controller
{
  // public function addfriends(Request $request)
  // {
  //   $user_id = Auth::user()->id;
  //   $status = Auth::user()->subs_status;
  //   $validator = Validator::make($request->all(), [
  //     'friend_to' => 'required',
  //   ]);

  //   if ($validator->fails()) {
  //     return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
  //   }
  //   if ($status == 1) {
  //     $sendrequest = Friend::where('friend_to', $user_id)->first();
  //     if ($sendrequest) {
  //       return response()->json(['status' => false, 'message' => "Your Are Not Able to Send Friend Request"], 200);
  //     } else {
  //       $data = new Friend;
  //       $data->friend_from = $user_id;
  //       $data->friend_to = $request->friend_to;
  //       $data->save();
  //       return response()->json(['status' => true, 'message' => "Add Friends Sucessfully", 'data' => $data], 200);
  //     }
  //   } else {
  //     return response()->json(['status' => false, 'message' => "Your have Not Taken Subscription"], 200);
  //   }
  // }
  // public function getfriends()
  // {
  //   $user_id = Auth::user()->id;

  //   $friends = Friend::join('users', 'users.id', '=', 'friends.friend_from')
  //     ->where('friend_from', $user_id)->where('friends.status', 'Accept')
  //     ->select('users.first_name', 'users.last_name', 'users.user_image', 'friends.status', 'friends.friend_to')
  //     ->get();

  //   $data = [];
  //   foreach ($friends as $key => $friend) {
  //     //$filling = Fillings::where('user_id',$friend->friend_to)->first();
  //     $data[] = [
  //       "first_name" => $friend->first_name,
  //       "last_name" => $friend->last_name,
  //       "user_image" => ($friend->user_image) ? url('user_image/' . $friend->user_image) : url('user_image/demo.jpeg'),
  //       "friend_id" => $friend->friend_to,
  //       "status" => $friend->status,
  //       //"review_massge"=>$filling->review_massge,
  //     ];
  //   }

  //   return response()->json(['status' => true, 'message' => "Accept Friend Lists", 'data' => $data], 200);
  // }

  public function showresponse()
  {
    $user_id = Auth::user()->id;
    $usersrequest = Friend::join('users', 'users.id', '=', 'friends.friend_from')->where('friend_from', $user_id)->where('friends.status', 'Send')->select('users.first_name', 'users.last_name', 'users.user_image', 'friends.status', 'friends.friend_to', 'friends.id', 'friends.created_at', 'friends.updated_at')->get();
    // dd($usersrequest);
    $data = [];
    if (count($usersrequest) > 0) {
      foreach ($usersrequest as $key => $friends) {
        $data[] = [
          "Requested id is" => $friends->id,
          "First Name" => $friends->first_name,
          "last Name" => $friends->last_name,
          "User Image" => url('user_image/' . $friends->user_image),
          "Requested Friend Id" => $friends->friend_to,
          "Friend Status" => $friends->status,
          "Created Date" => $friends->created_at,
          "Updated Date" => $friends->updated_at,

        ];
      }

      return response()->json(['status' => true, 'data' => $data], 200);
    } else {
      return response()->json(['status' => false, 'message' => 'no requests'], 200);
    }
  }
  public function friendresponse(Request $request)
  {
    $user_id = Auth::user()->id;
    $validator = Validator::make($request->all(), [

      'id' => 'required',
      'status' => 'required',

    ]);

    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }


    $norequest = Friend::where('friend_from', $user_id)->where('status', 'Send')->first();

    if (!$norequest) {

      return response()->json(['status' => false, 'message' => "Your Have Not Friend Request "], 200);
    } else {
      $firendrps = Friend::where('friend_from', $user_id)->where('status', 'Send')->where('id', $request->id)->update(['status' => $request->status]);

      if ($firendrps == 1) {

        $massg['Upate data'] = "Your Data Update";
      } else {
        $massg['Upate data'] = "Nothing to update";
      }
      return response()->json(['status' => true, 'message' => $massg], 200);
    }
  }

  public function singledetails(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'user_id' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }

    $deltal = User::where('id', $request->user_id)->first();

    if (!$deltal) {

      return response()->json(['status' => true, 'message' => "This User Id Not Exits"], 200);
    } else {
      $settings = UserPrivacySettings::where('user_id', $request->user_id)->first();
        
        $privacy_settings = [
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
        $privacy_setting = [];
        foreach ($privacy_settings as $key => $value) {
            # code...
            $privacy_setting[] = [
                'name' => $data_name[$key],
                'key' => $key,
                'status' => $value
            ];
        }

      $data = [
        'first_name' => ($deltal->first_name)?$deltal->first_name:'',
        'last_name' => ($deltal->last_name)?$deltal->last_name:'',
        'email' => ($deltal->email)?$deltal->email:'',
        'phone' => $deltal->phone, //($deltal->phone)?$deltal->phone:'',
        'image' => ($deltal->user_image)?url('user_image/' . $deltal->user_image):url('user_image/demo.jpeg'),
        'privacy_setting' => $privacy_setting,
      ];

      $todayDate = Carbon::now()->format('Y-m-d');
      $total = Tag::where('budgeted_start_time', '<>', '00:00')
        ->where('tags.budgeted_start_time', '<>', NULL)
        ->where('tags.budget_end_time', '<>', NULL)
        ->where('tags.user_id', $request->user_id)
        ->where('tags.date', $todayDate)
        ->count();
      
      $completed = Tag::where('budgeted_start_time', '<>', '00:00')
        ->where('tags.budgeted_start_time', '<>', NULL)
        ->where('tags.budget_end_time', '<>', NULL)
        ->where('tags.user_id', $request->user_id)
        ->where('tags.date', $todayDate)
        ->where('tags.status', '1')
        ->count();
      
      $total = ($total > 0)?$total:1;
      $completed = ($completed > 0)?$completed:1;

      $achievement = number_format((($completed / $total) * 100),2,'.',''); 
        
      $data['achievement'] = $achievement." % Goals Completed";
      return response()->json(['status' => true, 'message' => "Single Friend Details Show", 'data' => $data], 200);
    }
  }


  // my work

  public function addfriends(Request $request)
  {
    $user_id = Auth::user()->id;
    $status = Auth::user()->subs_status;
    $validator = Validator::make($request->all(), [
      'email' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }
    // if($status == 1){
      $userdata = User::where('email', $request->email)->first();
      if(!empty($userdata)){
        $recieverid = $userdata->id;
        $sendrequest = Friend::where('friend_from', $user_id)->where('friend_to',$recieverid)->where('status','Send')->first();
        if($sendrequest){
          return response()->json(['status' => true, 'message' => "You already send friend request!",'data' => null], 200);
        }

        $sendrequest = Friend::where('friend_from', $user_id)->where('friend_to',$recieverid)->where('status','Accept')->first();
        if(!empty($sendrequest)){
          return response()->json(['status' => false, 'message' => "You already friend with this user!",'data' => null], 200);
        }else{
          $data = new Friend;
          $data->friend_from = $user_id;
          $data->friend_to = $recieverid;
          $data->status = 'Send';
          $data->save();

          $userself = Auth::user();
          $msg = $userself->first_name.' send you a friend request.';
          $this->sendNotification('Friend Request Received', $msg, $recieverid, []);
          return response()->json(['status' => true, 'message' => "Friend Added Successfully", 'data' => $data], 200);
        }
      } else {
        return response()->json(['status' => false, 'message' => "This is not Lifetrack user",'data' => null], 200);
      }

    // }else{
    //   return response()->json(['status' => false, 'message' => "Your have Not Taken Subscription",'data' => null], 200);
    // }
  }

  public function myfriendrequest(Request $request)
  {
    $user_id = Auth::user()->id;
    $sendrequest = Friend::where('friend_to',$user_id)->where('status','Send')->get();
    if(count($sendrequest)>0){
      $data = [];
      foreach($sendrequest as $value){
        $user = User::where('id',$value->friend_from)->first();
        if($user)
        $data[] = [
          "user_id" =>$user->id,
          "first_name" => $user->first_name,
          "last_name"  => $user->last_name,
          "user_image" => ($user->user_image) ? url('user_image/' . $user->user_image) : url('user_image/demo.jpeg'),
        ];
      }
      return response()->json(['status' => true, 'message' => "Your Friend Request List", 'data' => $data], 200);
    }else{
      return response()->json(['status' => false, 'message' => "You don't have any friend request", 'data' => []], 200);
    }

  }

  public function rejectfriendrequest(Request $request){
    $validator = Validator::make($request->all(), [
      'user_id' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }

    $user_id = Auth::user()->id;
    
    $data = Friend::where('friend_from', $request->user_id)->where('status', 'Send')->where('friend_to', $user_id)->first();

    if(!empty($data)){
      $data->status = 'Decline';
      $data->save();

      $userself = Auth::user();
      $msg = $userself->first_name.' rejected your friend request.';
      $this->sendNotification('Friend Request Rejected', $msg, $request->user_id, []);
      return response()->json(['status' => true, 'message' => "Rejected Successfully", 'data' => $data], 200);
    }else{
      return response()->json(['status' => false, 'message' => "Invalid data"], 200);
    }
  }

  public function acceptfriendrequest(Request $request) {
    $validator = Validator::make($request->all(), [
      'user_id' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }
    $user_id = Auth::user()->id;
    $data = Friend::where('friend_from', $request->user_id)->where('status', 'Send')->where('friend_to', $user_id)->first();
    
    if(!empty($data)){
      $data->status = 'Accept';
      $data->save();
      $user = User::where('id', $request->user_id)->first();
      
      $userself = Auth::user();
      $msg = $userself->first_name.' accepted your friend request.';
      $this->sendNotification('Friend Request Accepted', $msg, $user->id, []);

      return response()->json(['status' => true, 'message' => "Accepted Successfully", 'data' => $data], 200);
    }else{
      return response()->json(['status' => false, 'message' => "Invalid data"], 200);
    }
  }

  public function pages(Request $request)
  {
    # code...
    $validator = Validator::make($request->all(), [
      'slug' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'data' => []], 200);
    }

    $page = Page::where('slug', $request->slug)->first();
    return response()->json(['status' => true, 'message' => "Page data", 'data' => $page], 200);
  }

  public function getfriends()
  {
    $user_id = Auth::user()->id;
    
    $friends = Friend::where('friend_from', $user_id)->orWhere('friend_to', $user_id)->where('status', 'Accept')->get();
    
    $data = [];
    $use_keys = [];
    if(!empty($friends)){
      foreach($friends as $key => $friend) {

        if($friend->friend_from == $user_id) {
          $user = User::where('id', $friend->friend_to)->first();
          $filling = Fillings::where('user_id',$friend->friend_to)->first();
        } else {
          $user = User::where('id', $friend->friend_from)->first();
          $filling = Fillings::where('user_id',$friend->friend_from)->first();
        }

        if($user) {
          // 
          if($user_id != $user->id) {

            if (!in_array($user->id, $use_keys) ) {
              // 
              $use_keys[] = $user->id;
                $settings = UserPrivacySettings::where('user_id', $user->id)->first();
                $privacy_setting = [
                    'profile_photo' => (isset($settings->profile_photo)&& $settings->profile_photo)?true:false,
                    'mood_update' => (isset($settings->mood_update)&& $settings->mood_update)?true:false,
                    'charts' => (isset($settings->charts)&& $settings->charts)?true:false,
                    'activity' => (isset($settings->activity)&& $settings->activity)?true:false,
                    'group_access' => (isset($settings->group_access)&& $settings->group_access)?true:false,
                ]; 
              $data[] = [
                'user_id' => $user->id,
                "first_name" => ($user->first_name)?$user->first_name:'',
                "last_name"  => ($user->last_name)?$user->last_name:'',
                "user_image" => ($user->user_image) ? url('user_image/' . $user->user_image) : url('user_image/demo.jpeg'),
                "status" => $friend->status,
                "review_massge"=>$filling->review_massge??'',
                "privacy_setting"=>(object)$privacy_setting,
              ];
            }
          }
        }
        
      }

      return response()->json(['status' => true, 'message' => "My Friend Lists", 'data' => $data], 200);
    
    }else{
      return response()->json(['status' => false, 'message' => "You don't have any friend"], 200);
    }
  }
}
