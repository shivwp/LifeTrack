<?php

namespace App\Http\Controllers\Api;

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\Notifications\UserRegister;
use App\PasswordReset as AppPasswordReset;
use App\Models\Role;
use App\Models\User;
use App\Models\Token;
use Carbon\Carbon;
use Validator;
use Str;
use App\Setting;
use App\UserVerifyToken;
use App\Models\MailTemplate;
use App\Models\Mails;
use App\Mail\Signup;
use App\Newsletter as Chimp;
use DrewM\MailChimp\MailChimp;
use Newsletter;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\RegisterMail;
use App\PhoneTemp;
use App\UserCompany;
use Twilio\Rest\Client;
use DB;
use Image as Img;
use App\Notifications;
use App\Models\Product;
use App\Models\listmanagement;
use App\Models\listmeta;
use App\Models\store;
use App\Models\FavouriteProduct;
use App\Models\listitem;
use App\Models\InvaitedUser;
use App\Models\Category;
use App\Models\Activity;
use App\Models\Tag;



class UserApiController extends Controller
{

  public function login(Request $request)
  {
    $user = Auth::user();

    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

      $verfyied = User::where('email', $request->email)->first();

      if (!$verfyied->otp_verfied == 1) {

        return response()->json(['status' => false, 'message' => 'You are  Not Verfied User',], 200);
      }
      $verfyied->token = $request->fcm_token;
      $verfyied->save(); // = $request->$fcm_token;
      $data['token'] = auth()->user()->createToken('User Token')->accessToken;


      $users = [
        'id' => $verfyied->id,
        'name' => $verfyied->first_name,
        'email' => $verfyied->email,
        'status' => $verfyied->status,
        'verfy_user' => $verfyied->verfy_user,
        'otp_verfied' => $verfyied->otp_verfied,
        'otp' => $verfyied->otp,
        'phone' => $verfyied->phone,
        'email_verified_at' => $verfyied->email_verified_at,
        'image' => url('user_image/' . $verfyied->user_image),
        'email_verified_at' => $verfyied->email_verified_at,
        'customer_id' => $verfyied->customer_id,
        'token' => $verfyied->token,
        'is_approved' => $verfyied->is_approved,
        'subs_status' => ($verfyied->subs_status)?$verfyied->subs_status:"0",
        'created_at' => $verfyied->created_at,
        'updated_at' => $verfyied->updated_at,
      ];

      return response()->json(['status' => true, 'message' => "Your account logged in successfully", 'data' => $data, 'users' => $users], 200);
    } else {
      return response()->json(['status' => false, 'message' => 'These credentials do not match our records.', 'user' => Null], 200);
    }
  }
  /**
   * Register api
   *
   * @return \Illuminate\Http\Response
   */


  public function signup_otp_send(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'email' => 'required|email|:users',
      // 'password' => 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/'
    ]);
    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }

    $otp = random_int(100000, 999999);

    $mail_data  = MailTemplate::where('mails.massage_category', 'Singup')->first();



    $config = [
      'form_email' => $mail_data->form_email,
      "reply_email" => $mail_data->reply_email,
      'subject' => $mail_data->subject,
      'form_name' => $mail_data->form_name,
      'massage_content' => $mail_data->massage_content,
      'otp' => $otp,
      'password' => '',
      'email' => $request->email,
    ];

    $user_email = $request->email;
    Mail::Send('admin.users.sendotp', $config, function ($messages) use ($user_email) {

      $messages->to($user_email);
      $messages->subject('LifeTrack');
    });

    return response()->json(['status' => true, 'otp' => $otp, 'message' => "We have Send You An 6 Digit Password To Your Registred."]);
  }


  public function sign_up(Request $request)
  {


    $validator = Validator::make($request->all(), [
      'email' => 'required|email|unique:users',
      'password' => 'required|min:6|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
    ]);

    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }
    $user = Auth::user();
    $otp = random_int(100000, 999999);

    $role = 4;
    $password = Hash::make($request->password);

    $data = new User;
    $data->email = $request->email;
    $data->password = $password;
    $data->first_name = $request->first_name;
    $data->last_name = $request->last_name;
    $data->status = '1';
    $data->verfy_user = '0';
    $data->otp = $otp;

    $data->save();
    $userdata = User::where('id', $data->id)->first();
    $token = $data->createToken('User Token')->accessToken;
    $data->roles()->sync($role);
    $config = [
      'name' => $request->first_name,
      'email' => $request->email,
      'password' => '',
      'otp' => $otp,
    ];

    $user_email = $request->email;
    Mail::Send('admin.users.mail', $config, function ($messages) use ($data, $user_email) {
      $messages->to($user_email);
      $messages->subject('LifeTrack');
    });

    $otp = $userdata['otp']; 
    unset($userdata['otp']);

    $userdata['otp'] = (int)$otp;
    return response()->json(['status' => true, 'message' => "Your account is registred successfully , Please do login now and enjoy ", 'token' => $token, 'data' => $userdata], 200);
  }

  public function very_otp(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'otp' => 'required|min:6|numeric',
    ]);

    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }

    $user = User::where('email', $request->email)->first();
    if ($user) {


      if ($user->email == $request->email &&  $user->otp == $request->otp) {


        $user->otp_verfied = 1;
        $user->otp = 0;
        $user->save();

        return response()->json(['status' => true, 'message' => "Your Email Id And Otp  Matched",], 200);
      } else {

        return response()->json(['status' => false, 'message' => "Your Email Id And Otp Not Matched",], 200);
      }
    } else {
      return response()->json(['status' => false, 'message' => "Your Email Id Not Matched",], 200);
    }
  }

  public function  resendotpusers(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'email'  => 'required|email',

    ]);
    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'user' => Null], 200);
    }


    $otp = random_int(100000, 999999);
    $user = User::where('email', '=', $request->email)->first();
    if (!$user) {

      return response()->json(['status' => false, 'message' => "Your Email Id Not Registered"], 200);
    }
    $user->otp = $otp;
    $user->save();

    if ($user) {

      $data = [
        'email' => $request->email,
        'otp' => $otp,
      ];

      $user_email = $request->email;
      Mail::Send('admin.users.resendotpac', $data, function ($messages) use ($user, $user_email) {

        $messages->to($user_email);
        $messages->subject('LifeTrack');
      });

      return response()->json(['status' => true, 'message' => "Account  Verification Otp  Send Your Email Successfully", 'users_resendotp' => $data], 200);
    }
  }
  public function forget_password_otp(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'email'  => 'required|email',
    ]);

    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'user' => Null], 200);
    }

    $user = User::where('email', '=', $request->email)->first();

    $token = random_int(100000, 999999);

    if ($user == '') {
      return response()->json(['status' => false, 'message' => 'This email is not registered with us , please recheck it.', 'user' => Null], 200);
    } else {
      // 
      $mail_data  = MailTemplate::where('mails.massage_category', 'forget_password')->first();

      $config = [
        'form_email' => $mail_data->form_email,
        "reply_email" => $mail_data->reply_email,
        'subject' => $mail_data->subject,
        'form_name' => $mail_data->form_name,
        'massage_category' => $mail_data->massage_category,
        'name' => $request->first_name,
        'email' => $request->email,
        'password' => '',
        'token' => $token,
      ];
      $user->otp = $token;
      $user->save();

      $user_email = $request->email;
      Mail::Send('admin.users.forgetmail', $config, function ($messages) use ($user_email) {

        $messages->to($user_email);
        $messages->subject('LifeTrack');
      });
    }

    return response()->json(['status' => true, 'otp' => $token, 'message' => "We have Send You An 6 Digit Password To Your Registred Mail Id."]);
  }

  public function reset_password(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required|min:6|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
      'confirm_password' => 'required|same:password',
    ]);
    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }

    $data = User::where('email', $request->email)->first();

    $password = Hash::make($request->password);
    $user_id = $data->id;
    $user = User::findOrFail($user_id);
    $user->password = $password;

    $user->save();

    $mail_data  = MailTemplate::where('mails.massage_category', 'Singup')->first();

    $config = [
      'form_email' => $mail_data->form_email,
      "reply_email" => $mail_data->reply_email,
      'subject' => $mail_data->subject,
      'form_name' => $mail_data->form_name,
      'massage_category' => $mail_data->massage_category,
      'name' => $request->first_name,
      'email' => $request->email,
      'token' => '',
      'password' => $request->password
    ];

    $user_email = $request->email;
    Mail::Send('admin.users.resetpassword', $config, function ($messages) use ($user_email) {

      $messages->to($user_email);
      $messages->subject('Lifetrack');
    });

    return response()->json(['status' => true, 'message' => "Your password has been changed successfully, Please do login", 'user' => $user], 200);
  }


  public function resend_otp(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
    ]);

    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }
    if (User::where('email', $request->email)->first()) {


      $token      = random_int(100000, 999999);

      $mail_data  = MailTemplate::where('mails.massage_category', 'Singup')
        ->first();

      $config = [
        'form_email' => $mail_data->form_email,
        "reply_email" => $mail_data->reply_email,
        'subject' => $mail_data->subject,
        'form_name' => $mail_data->form_name,
        'massage_category' => $mail_data->massage_category,
        'name' => $request->first_name,
        'email' => $request->email,
        'token' => $token,

      ];

      $user_email = $request->email;
      $user = User::where('email', $request->email)->first();
      $user->otp = $token;
      $user->save();
      Mail::Send('admin.users.resendotp', $config, function ($messages) use ($user_email) {

        $messages->to($user_email);
        $messages->subject('Lifetrack');
      });
    } else {
      return response()->json(['status' => 'false', 'message' => "Your Email is Not Registered"], 200);
    }
    return response()->json(['status' => true, 'message' => "Mail Send Your Registered Email Id", 'token' => $token], 200);
  }


  public function update_password(Request $request)
  {
    $user = Auth::user();
    $userPassword = $user->password;
    $validator = Validator::make($request->all(), [
      'current_password' => 'required',
      'password' => 'required|min:6',
      // 'conformpassword' => 'required|min:8|string|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/|same:password',
    ]);
    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }

    if (Hash::check($request->current_password,  $userPassword)) {
      $user->password = Hash::make($request->password);
      $user->save();
      return response()->json(['status' => true, 'message' => "Password Updated!"], 200);
    } else {
      return response()->json(['status' => false, 'message' => "Old Password not match!"], 200);
    }
  }

  public function update_profile(Request $request)
  {

    $user = Auth::user();
    $user_id = $user->id;
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      // 'user_image' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }

    $profile = User::find($user_id);
    $profile->first_name = $request->name;
    $profile->phone = $request->phone;

    if ($image = $request->file('user_image')) {
      $destinationPath = 'user_image/';
      $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
      $image->move($destinationPath, $profileImage);
      $profile->user_image = $profileImage;
    }

    $udate_profile = $profile->save();

    if ($udate_profile) {

      return response()->json(['status' => true, 'message' => "your profile is updated"], 200);
    } else {
      return response()->json(['status' => false, 'message' => "Your profile is not Updated"], 200);
    }
  }

  public function get_profile()
  {

    $user = Auth::user();
    $users = [
        'id' => $user->id,
        'name' => ($user->first_name)??'',
        'email' => $user->email,
        'status' => $user->status,
        'verfy_user' => $user->verfy_user,
        'otp_verfied' => $user->otp_verfied,
        'otp' => $user->otp,
        'phone' => ($user->phone)??'',
        'email_verified_at' => ($user->email_verified_at)??'',
        'image' => url('user_image/' . $user->user_image),
        'customer_id' => ($user->customer_id)??'',
        'token' => ($user->token)??'',
        'is_approved' => $user->is_approved,
        'subs_status' => ($user->subs_status)?$user->subs_status:"0",
        'created_at' => $user->created_at,
        'updated_at' => $user->updated_at,

      ];
    return response()->json(['status' => true, 'message' => "Show Users Details", 'data' => $users], 200);
  }


  public function get_category()
  {
    $categories = Category::where('parent_id', 0)->select('title', 'id')->get();
    return response()->json(['status' => true, 'message' => "Show Users category", 'categories' => $categories], 200);
  }

  public function get_subcategory()
  {
    $subcategories = Category::where('parent_id', "<>", 0)->select('title', 'id', 'parent_id')->get();
    return response()->json(['status' => true, 'message' => "Show Users subcategory", 'subcategories' => $subcategories], 200);
  }

  public function add_activity(Request $request)
  {
    $userid = Auth::User()->id;

    $validator = Validator::make($request->all(), [
      'activity' =>   'required',
      'parent_catgory' => 'required',
      'sub_category' =>  'required',
      // 'starttime' => 'required|date_format:H:i',
      // 'endtime' => 'required|date_format:H:i|after:starttime',
      'selectprivacy' => 'required',
      'selectcolor' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }

    $act_data = new Activity;
    $act_data->username = $userid;
    $act_data->activity = $request->activity;
    $act_data->starttime = ($request->starttime)??'';
    $act_data->endtime = ($request->endtime)??'';
    $act_data->selectprivacy = $request->selectprivacy;
    $act_data->selectcolor = $request->selectcolor;
    $act_data->parent_catgory = $request->parent_catgory;
    $act_data->sub_category = $request->sub_category;
    $act_data->save();

    return response()->json(['status' => true, 'message' => 'Tag added successfully.', 'activity' => $act_data], 200);

  }


  public function updateActivity(Request $request)
  {
    $userid = Auth::User()->id;
    
    $validator = Validator::make($request->all(), [
      'activity' =>   'required',
      // 'parent_catgory' => 'required',
      // 'sub_category' =>  'required',
      // 'starttime' => 'required|date_format:H:i',
      // 'endtime' => 'required|date_format:H:i|after:starttime',
      // 'selectprivacy' => 'required',
      // 'selectcolor' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }

    Activity::updateOrCreate(
      ['id' => $request->activity_id],
      [
        'username' => $userid,
        'activity' => $request->activity,
        'starttime' => $request->starttime,
        'endtime' => $request->endtime,
        'selectprivacy' => $request->selectprivacy,
        'selectcolor' => $request->selectcolor,
        'parent_catgory' => $request->parent_catgory,
        'sub_category' => $request->sub_category,
      ]
    );
    $data = Activity::where('id',$request->activity_id)->first();
    return response()->json(['status' => true, 'message' => 'Tag updated successfully.', 'data' => $data], 200);

  }

  public function wellcomemsg()
  {
    $name = Auth::User()->first_name;
    $data["username"] = "Hii" . " " . $name;
    $data["Content 1"] = "rttttttttttt";
    $data["Content 2"] = "rttttttttttt";
    $data["Content 3"] = "rttttttttttt";

    return response()->json(['status' => true, 'message' => "WellCome Screen", 'data' => $data], 200);
  }

  public function get_activity()
  {

    $userid = Auth::User()->id;
    $users = Activity::where('username', $userid)->get();
    /*Activity::Select('activitys.id','activitys.activity', 'activitys.selectcolor','activitys.selectprivacy','activitys.starttime','activitys.endtime','cat.title as parent_catgory', 'sub_cat.title as sub_category')
                    ->join('categories as cat', 'cat.id', '=', 'activitys.parent_catgory')
                    ->join('categories as sub_cat', 'sub_cat.id', '=', 'activitys.sub_category')
                    ->get();    
                     */
    $data = [];
    foreach ($users as $user) {
      $data[] = [
        'id' => $user->id,
        'userid' => $user->username,
        'activity' => $user->activity,
        'parent_catgory' => $user->parent_catgory,
        'sub_category' => $user->sub_category,
        'starttime' => $user->starttime,
        'endtime' => $user->endtime,
        'selectcolor' => $user->selectcolor,
        'selectprivacy' => $user->selectprivacy,
        'created_at' => $user->created_at,
        'updated_at' => $user->updated_at,
      ];
    }

    return response()->json(['status' => true, 'message' => "Activity Tags List", 'activity' => $data], 200);
  }
  public function deleteactivity(Request $request)
  {

    $user_id = Auth::user()->id;
    $validator = Validator::make($request->all(), [
      'id' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }

    $activity = Activity::where('username', $user_id)->first();
    if ($activity) {

      $activiys = Activity::where('id', $request->id)->where('username', $user_id)->delete();

      if ($activiys) {
        return response()->json(['status' => true, 'message' => "Deleted Activity Tags successfully"], 200);
      } else {
        return response()->json(['status' => false, 'message' => " Your  Activity Tags All Ready  Deleted "], 200);
      }
    }
  }
  public function addActivity(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'date' => 'required|date_format:Y-m-d',
      'budgeted_start_time' => 'required|date_format:H:i',
      'budget_end_time' => 'required|date_format:H:i|after:budgeted_start_time',
      'actual_start_time' => 'required|date_format:H:i',
      'actual_end_time' => 'required|date_format:H:i|after:actual_start_time',
    ]);

    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }

    $user_id = Auth::user()->id;
    $name = Auth::user()->name;

    $tagdata = new Tag();
    $tagdata->user_id = $user_id;
    $tagdata->tag_id = $request->tag_id;
    $tagdata->date = $request->date;
    $tagdata->budgeted_start_time = $request->budgeted_start_time;
    $tagdata->budget_end_time = $request->budget_end_time;
    $tagdata->actual_start_time = $request->actual_start_time;
    $tagdata->actual_end_time = $request->actual_end_time;
    $tagdata->save();

    return response()->json(['status' => true, 'message' => "Activity added successfully ", 'dailyactivity' => $tagdata], 200);
  }

  public function get_tag(Request $request)
  {
    $name = Auth::user()->name;
    $tages = Tag::select('tags.*', 'users.first_name', 'activitys.activity')->join('users', 'users.id', '=', 'tags.user_id')->join('activitys', 'activitys.id', '=', 'tags.tag_id')->get();
    
    foreach ($tages as $sku) {

      $data[] = [
       "name"=> Auth::user()->first_name,
       "activity"=> $sku->activity,
        "date"=>$sku->date,
        "budgeted_start_time"=>$sku->budgeted_start_time,
        "budget_end_time"=>$sku->budget_end_time,
        "actual_start_time"=>$sku->actual_start_time,
        "actual_end_time"=>$sku->actual_end_time,
        "status"=>$sku->status
      ];
    }

    return response()->json(['status' => true, 'message' => "Get Daily Activity Tags  Details ", 'dailyactivity' => $data], 200);
  }


  public function userNotificationStatus(Request $request)
  {
    # code...

    if (Auth::guard('api')->check()) {
      $user = Auth::guard('api')->user();
    }
    if(!$user) {
      return response()->json(['status' => false, 'message' => 'login token error!']);
    }
    $user_id = $user->id;

    $validator = Validator::make($request->all(), [
      'notification' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }

    $parameters = $request->all();
    extract($parameters);

    User::where('id', $user_id)->update([
      'notification' => ($notification)?1:0,
    ]);
    return response()->json(['status' => true, 'message' => "Notification status updated!"], 200);
  }

  public function userNotification(Request $request)
  {
    # code...

    if (Auth::guard('api')->check()) {
      $user = Auth::guard('api')->user();
    }
    if(!$user) {
      return response()->json(['status' => false, 'message' => 'login token error!']);
    }

    return response()->json(['status' => true, 'message' => "Notification status!", 'data' => ['notification' => ($user->notification)?true:false]], 200);
  }

  public function changePassword(Request $request)
  {
    # code...
    if (Auth::guard('api')->check()) {
      $user = Auth::guard('api')->user();
    }
    if(!$user) {
      return response()->json(['status' => false, 'message' => 'login token error!']);
    }

    $parameters = $request->all();
    extract($parameters);

    if (Hash::check($old_password, $user->password)) {
      // Success
      $user->password = Hash::make($new_password); 
      $user->save();
      return response()->json(['status' => true, 'message' => 'Password updated!']);
    } else {
      return response()->json(['status' => false, 'message' => 'old password not match!']);
    }

    

  }

}
