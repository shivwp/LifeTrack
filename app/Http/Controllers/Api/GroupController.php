<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;
use App\Models\Contactlist;
use Validator;
use Auth;
use DB; 
use Carbon\Carbon;
use App\Models\UserPrivacySettings;

class GroupController extends Controller
{
    public function creategroup(Request $request)
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        }
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'login token error!', 'data' => []]);
        }
        $user_id = $user->id;
        $subs_status = $user->subs_status;
        $validator = Validator::make($request->all(), [
            'groupname' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'data' => []], 200);
        }

        if ($subs_status == 1) {

            $grp = Group::where('groupname', $request->groupname)->first();

            if($grp && !isset($request->id)) {
                return response()->json(['status' => false, 'message' => 'Group already created!', 'data' => null], 200);
            }

            $group = Group::updateOrCreate(['id' => $request->id], [
                'create_by'   => $user_id,
                'groupname'   => $request->groupname,
                'descrption'  => $request->descrption,
            ]);
            
            $data = [
                'id' => $group->id,
                'create_by'   => $group->create_by,
                'groupname'   => $group->groupname,
                'descrption'  => $group->descrption,
                'created_at'  => $group->created_at,
                'updated_at'  => $group->updated_at,
            ];

            if ($request->id) {
                $message = "Group updated";
            } else {
                $message = "Group created";
                $contactlist = new Contactlist;
                $contactlist->user_id = $user_id;
                $contactlist->group_id = $group->id;
                $contactlist->admin = $group->create_by;
                $contactlist->accpet = true;
                $contactlist->save();
            }

            $user_ids = explode(',',$request->user_ids);
            if (count($user_ids) > 0) {
                
                foreach ($user_ids as $key => $id) {
                    # code...
                    if(User::where('id', $id)->first()) {

                        $contactlist = new Contactlist;
                        $contactlist->user_id = $id;
                        $contactlist->group_id = $group->id;
                        $contactlist->admin = $group->create_by;
                        $contactlist->save();

                        DB::table('notifications')->insert([
                            'user_id' => $id,
                            'title' => $user->first_name. ' invited to join group',
                            'description' => 'You were invited to join group.',
                            'notice_date' => Carbon::now()->format('Y-m-d'),
                            'status' => false
                        ]);
                    }
                }

                $userself = Auth::user();
                $msg = $userself->first_name.' Created a Group! You were invited to join group.';
                $res = $this->sendNotification('Group Created!', $msg, '', $user_ids);
            }

            return response()->json(['status' => true, 'message' => $message, 'data' => $data], 200);
        } else {
            return response()->json(['status' => false, 'message' => "Your have Not Taken Subscription", 'data' => null], 200);
        }
    }


    public function contactlist(Request $request)
    {
        // 
        $user_id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'group_id' => 'required',
            'user_ids' => 'required'
        ]);
        
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        }
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'login token error!', 'data' => []]);
        }

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'data' => []], 200);
        }

        // $groupreq = Contactlist::where('user_id', $user_id)->where('group_id', $request->group_id)->first();
        $data = [];
        $user_ids = explode(',',$request->user_ids);
        if (count($user_ids) == 0) {
            return response()->json(['status' => false, 'message' => "need to add minimum 1 user", 'data' => []], 200);
        } else {

            foreach ($user_ids as $key => $id) {
                # code...
                $has_user = Contactlist::where('user_id', $id)->where('group_id', $request->group_id)->first();
                $new_users = [];
                if(!$has_user) {
                    // 
                    $contactlist = new Contactlist;
                    $contactlist->user_id = $id;
                    $contactlist->group_id = $request->group_id;
                    $contactlist->admin = $user_id;
                    $contactlist->save();

                    DB::table('notifications')->insert([
                        'user_id' => $id,
                        'title' => $user->first_name. ' invited to join group',
                        'description' => 'You were invited to join group.',
                        'notice_date' => Carbon::now()->format('Y-m-d'),
                        'status' => false
                    ]);
                    $new_users[] = $id;
                }
            }

            $msg = $user->first_name.' invited you to join the group.';
            $res = $this->sendNotification('Invited to join group!', $msg, '', $new_users);

            return response()->json(['status' => true, 'message' => "Members added to groups.", 'data' => []], 200);
        }
    }

    public function responseToGroupRequest(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'request_id' => 'required',
            'status' => 'required'
        ]);
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        }
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'login token error!', 'data' => []]);
        }
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'data' => []], 200);
        }

        $contactlist = Contactlist::where('id', $request->request_id)->first();
        $contactlist->accpet = ($request->status == 'accept') ?true:false;
        $contactlist->save();

        $userself = Auth::user();
        $res_msg = ($request->status == 'accept')?"accepted":'rejected';
        $msg = $user->first_name.' '.$res_msg.' your invitation to join the group.';
        $res = $this->sendNotification('Group invitation '.$res_msg.'!', $msg, $contactlist->admin, []);

        return response()->json(['status' => true, 'message' => ($request->status == 'accept')?"Request accepted!":'Request decline!'], 200);
    }

    public function showgroup()
    {
        // 
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        }
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'login token error!', 'data' => []]);
        }
        $groups = Contactlist::select('group.*')->join('group', 'group.id', '=', 'contactlist.group_id')->where('contactlist.user_id', $user->id)->get();
        $data = [];
        foreach ($groups as $key => $value) {
            # code...
            $data[] = [
                "id" => $value->id,
                "groupname" => $value->groupname, 
                "descrption" => $value->descrption,
                "create_by" => $value->create_by,
                "created_at" => $value->created_at,
                "updated_at" => $value->updated_at,
            ];
        }
        return response()->json(['status' => true, 'message' => "Show Groups", 'data' => $data], 200);
    }

    public function contactlists()
    {
        $datas = Contactlist::all();
        $data = [];
        foreach ($datas as $key => $value) {
            # code...
            // $user = User::where('id', )
            $data[] = [
                "id" => $value->id,
                "user_id" => $value->user_id,
                "user_id" => $value->user_id,
                "group_id" => $value->group_id,
                "admin" => $value->admin,
                "accpet" => $value->accpet,
            ];
        }
        return response()->json(['status' => true, 'message' => "Show Contact Lists", 'data' => $data], 200);
    }

    public function deletegroup(Request $request)
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        }
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'login token error!', 'data' => []]);
        }

        $subs_status = $user->subs_status;
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        if ($subs_status == 1) {

            $group = Group::where('id', $request->id)->where('create_by', $user->id)->first();
            if (!$group) {
                return  response()->json(['status' => false, 'message' => "Group id not found "], 200);
            }
            $group->delete();

            return response()->json(['status' => true, 'message' => "Group deleted"], 200);
        } else {
            return response()->json(['status' => false, 'message' => "You Are Not Taken Subscription"], 200);
        }
    }

    public function singleGroup(Request $request) 
    {
        # code...
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        }
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'login token error!', 'data' => []]);
        }
        $validator = Validator::make($request->all(), [
            'group_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'data' => []], 200);
        }

        $parameters = $request->all();
        extract($parameters);

        $group = Group::where('id', $group_id)->with('members')->first();
        if(!$group) {
            return response()->json(['status' => false, 'message' => 'Group not found', 'data' => []], 200);
        }
        $members = [];
        if(count($group->members)>0){
            // 
            foreach ($group->members as $key => $value) {
                # code...
                $user = User::where('id', $value->user_id)->first();
                if($user) {
                    $settings = UserPrivacySettings::where('user_id', $user->id)->first();
                    $privacy_setting = [
                        'profile_photo' => (isset($settings->profile_photo)&& $settings->profile_photo)?true:false,
                        'mood_update' => (isset($settings->mood_update)&& $settings->mood_update)?true:false,
                        'charts' => (isset($settings->charts)&& $settings->charts)?true:false,
                        'activity' => (isset($settings->activity)&& $settings->activity)?true:false,
                        'group_access' => (isset($settings->group_access)&& $settings->group_access)?true:false,
                    ]; 
                    $members[] = [
                        "user_id" =>$user->id,
                        "first_name" => $user->first_name,
                        "last_name"  => $user->last_name,
                        "user_image" => ($user->user_image) ? url('user_image/' . $user->user_image) : url('user_image/demo.jpeg'),
                        "privacy_setting" => (object)$privacy_setting
                    ];
                }
            }
        }
        unset($group['created_at']);
        unset($group['updated_at']);
        unset($group['members']);
        $group['member_data'] = $members;
        $group['descrption'] = ($group->descrption)?$group->descrption:''; 
        return response()->json(['status' => true, 'message' => 'Group detail!', 'data' => $group], 200);
    }

    public function removeGroupMembers(Request $request)
    {
        # code...
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
        }
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'login token error!']);
        }
        $validator = Validator::make($request->all(), [
            'group_id' => 'required|numeric',
            'user_ids' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        $parameters = $request->all();
        extract($parameters);

        $user_ids = explode(',',$user_ids);

        foreach ($user_ids as $key => $id) {
            # code...
            // if(User::where('id', $id)->first()) {
                
            // }
            $contactlist = Contactlist::where('user_id', $id)->delete();
            // $contactlist->delete();
        }

        return response()->json(['status' => true, 'message' => 'Members removed successfully!']);
    }
}
