<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\FillingController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\JournalController;
use App\Http\Controllers\Api\FriendController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\HomeApiController;

use App\Http\Controllers\Api\SubscriptionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::post('signup_otp', [UserApiController::class, 'signup_otp_send']);


Route::post('signup', [UserApiController::class, 'sign_up']);
Route::post('veryotp', [UserApiController::class, 'very_otp']);


Route::post('forget_password_otp', [UserApiController::class, 'forget_password_otp']);
Route::post('reset_password', [UserApiController::class, 'reset_password']);
Route::post('resend_otp', [UserApiController::class, 'resend_otp']);
Route::post('resendotpusers', [UserApiController::class, 'resendotpusers']);


Route::post('plans', [SubscriptionController::class, 'plan']);



Route::post('login', [UserApiController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('update_password', [UserApiController::class, 'update_password']);
    Route::post('update_profile', [UserApiController::class, 'update_profile']);
    Route::get('get_profile', [UserApiController::class, 'get_profile']);
    Route::get('wellcomemsg', [UserApiController::class, 'wellcomemsg']);
    Route::post('add_activity_tag', [UserApiController::class, 'add_activity']);
    Route::post('update-activity', [UserApiController::class, 'updateActivity']);
    Route::get('get_activity_tag', [UserApiController::class, 'get_activity']);
    Route::post('deleteactivity_tag', [UserApiController::class, 'deleteactivity']);
    Route::post('singleuser', [UserApiController::class, 'singleuser']);
    Route::post('add-activity', [UserApiController::class, 'addActivity']);
    Route::get('get-tags', [UserApiController::class, 'get_tag']);
    Route::post('add-activities', [TagController::class, 'addActivities']); // Add Tags in Activiey list
    Route::get('get_activity', [TagController::class, 'get_tag']);
    Route::post('daywisedata', [TagController::class, 'daywisedata']);
    Route::post('weakwisedetals', [TagController::class, 'weakwisedetals']);
    Route::post('monthlydetals', [TagController::class, 'monthlydetals']);

    // Daily Activity Report
    Route::post('daily-activity-report', [TagController::class, 'dailyActivityReport']);

    // Weekly Activity Report
    Route::post('weekly-activity-report', [TagController::class, 'weeklyActivityReport']);

    // Weekly Activity Report
    Route::post('monthly-activity-report', [TagController::class, 'monthlyActivityReport']);

    // Custom Activity Report
    Route::post('custom-activity-report', [TagController::class, 'customActivityReport']);
    
    // Category Based Activity Report
    Route::post('category-based-activity-report', [TagController::class, 'categoryBasedActivityReport']);
    
    Route::post('update-tag-status', [TagController::class, 'updateTagStatus']);
    

    Route::post('addfilling', [FillingController::class, 'addreview']);
    Route::get('getfilling', [FillingController::class, 'getfilling']);
    Route::post('notifications', [NotificationController::class, 'notifications']);
    Route::get('shownotications', [NotificationController::class, 'getnotice']);
    Route::post('getjournal', [JournalController::class, 'journal']);
    Route::post('addjournal', [JournalController::class, 'addjournal']);
    Route::get('showsinglejnrl', [JournalController::class, 'showsinglejnrl']);
    Route::post('singlejnrl', [JournalController::class, 'singlejnrl']);

    Route::post('addfriends', [FriendController::class, 'addfriends']);
    //my
    Route::get('myfriendrequest', [FriendController::class, 'myfriendrequest']);
    Route::post('rejectfriendrequest', [FriendController::class, 'rejectfriendrequest']);
    Route::post('acceptfriendrequest', [FriendController::class, 'acceptfriendrequest']);
    

    Route::get('getfriends', [FriendController::class, 'getfriends']);
    Route::get('showresponse', [FriendController::class, 'showresponse']);
    Route::post('friendresponse', [FriendController::class, 'friendresponse']);
    Route::post('singlefriendprofile', [FriendController::class, 'singledetails']);
    Route::post('creategroup', [GroupController::class, 'creategroup']);
    Route::post('addcontactlist', [GroupController::class, 'contactlist']);
    Route::get('showgroup', [GroupController::class, 'showgroup']);
    Route::get('contactlist', [GroupController::class, 'contactlists']);
    Route::post('deletegroup', [GroupController::class, 'deletegroup']);
    Route::post('single-group', [GroupController::class, 'singleGroup']);
    Route::post('remove-groupe-member', [GroupController::class, 'removeGroupMembers']);
    
    Route::get('get_category', [UserApiController::class, 'get_category']);
    Route::get('get_subcategory', [UserApiController::class, 'get_subcategory']);

    Route::post('user-notification-status', [UserApiController::class, 'userNotificationStatus']);
    Route::post('user-notification', [UserApiController::class, 'userNotification']);
    Route::post('response-to-group-request', [GroupController::class, 'responseToGroupRequest']);
    
    Route::post('change-password', [UserApiController::class, 'changePassword']);

    Route::post('subscriptionPayment', [SubscriptionController::class, 'subscriptionPayment']);
    
    Route::post('get-single-user-activities', [TagController::class, 'getSingleUserActivities']);

    // user privacy setting
    Route::post('show-privacy-settings', [HomeApiController::class, 'showPrivacySettings']);
    // update privacy settings 
    Route::post('update-privacy-settings', [HomeApiController::class, 'updatePrivacySettings']);
    
});

Route::post('page', [FriendController::class, 'pages']);
Route::post('send-notification', [HomeApiController::class, 'sendFcmNotification']);


Route::get('/clear-cache', function () {
    // 
    Artisan::call('cache:clear');
    Artisan::call('optimize');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return "Cache is cleared";
});
