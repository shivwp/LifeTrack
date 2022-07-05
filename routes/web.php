<?php



use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\HomeApiController;

use App\Http\Controllers\UserController;

use App\Http\Controllers\admin\UsersController;

use App\Http\Controllers\admin\RolesController;

use App\Http\Controllers\admin\PermissionsController;

use App\Http\Controllers\admin\AttributeController;

use App\Http\Controllers\admin\AttributeValueController;

use App\Http\Controllers\admin\HomepageController;

use App\Http\Controllers\admin\SettingsController;

use App\Http\Controllers\admin\MenuController;

use App\Http\Controllers\admin\VendorSettingController;

use App\Http\Controllers\admin\GeneralSettingController;

use App\Http\Controllers\admin\DashboardController;

use App\Http\Controllers\admin\JournalManagementController;

use App\Http\Controllers\admin\CategoryController;

use App\Http\Controllers\admin\PageController;

use App\Http\Controllers\admin\MailController;

use App\Http\Controllers\admin\SubscriptionController;

use App\Http\Controllers\admin\ActivityController;








/*

|--------------------------------------------------------------------------

| Web Routes

|--------------------------------------------------------------------------

|

| Here is where you can register web routes for your application. These

| routes are loaded by the RouteServiceProvider within a group which

| contains the "web" middleware group. Now create something great!

|

*/

Route::redirect('/', '/login');


Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.', 'middleware' => ['auth']], function (){

     Route::get('/', 'HomeController@index')->name('home');

    Route::get('/', function () {

        return view('index');

    });

    Auth::routes();

    // email verify 
    Auth::routes(['verify' => true]);


    


    
    // usercount
    
    Route::get('update-user-count', [UsersController::class,'usercount'])->name('update-user-count');

    Route::get('update-journal-count', [UsersController::class,'journalcount'])->name('update-journaluser-count');

   
    //Users

    Route::resource('users', UsersController::class);

    //Roles

    Route::resource('roles', RolesController::class);

 

    //Permission

    Route::resource('permissions', PermissionsController::class);


   //Journal
    
    Route::resource('journalmang', JournalManagementController::class);

    //pages
        
    Route::resource('pages', PageController::class);
    
    //mails

    Route::resource('mails', MailController::class);


    //subscriptions

    Route::resource('subscriptions', SubscriptionController::class);

    //activity

    Route::resource('activity', ActivityController::class);
    Route::get('serech',[ActivityController::class,'serech'])->name('serech');
    
    Route::post('subcat', [ActivityController::class, 'getSubCategory'])->name('subcat');
     
    //Dashboard

    Route::resource('dashboard', DashboardController::class);


    
    //Category

    Route::resource('category', CategoryController::class);
    Route::post('category-pagination',[App\Http\Controllers\admin\CategoryController::class, 'pagination'])->name('category-pagination');
    //Attribute

    Route::resource('attribute', AttributeController::class);
    Route::get('add-value/{id}',[App\Http\Controllers\admin\AttributeController::class, 'addvalue']);
    Route::POST('save-value/{id}',[App\Http\Controllers\admin\AttributeController::class, 'saveatrvalue']);
    //Attribute value

    Route::resource('attribute-value', AttributeValueController::class);

    
     //generalsetting

    Route::resource('general-setting', GeneralSettingController::class)->name('*', 'general-setting');


    // Home Page

    Route::resource('homepage', HomepageController::class);





    // settings

      Route::resource('settings', SettingsController::class);



    //Vendor settings

    Route::resource('vendorsettings', VendorSettingController::class);
    Route::get('vendorsetting',[App\Http\Controllers\admin\VendorSettingController::class, 'index2'])->name('vendor-setting');
     Route::post('vendor-setting-update',[App\Http\Controllers\admin\VendorSettingController::class, 'storedata'])->name('vendor-setting-update');
     Route::get('vendorsetting-admin',[App\Http\Controllers\admin\VendorSettingController::class, 'index3'])->name('vendor-setting-admin');

    //Approve & Reject Vendor
     Route::get('vendor-approve/{id}',[App\Http\Controllers\admin\VendorSettingController::class, 'approveVendor'])->name('vendor-approve');
     Route::get('vendor-rejected/{id}',[App\Http\Controllers\admin\VendorSettingController::class, 'rejectVendor'])->name('vendor-rejected');

      
    // reviews

     


       Route::get('user',[App\Http\Controllers\admin\UsersController::class, 'index2'])->name('user-index');



     


  



    Route::get('get-category/{id}', [ProductController::class,'getCategory'])->name('get-category');


    });

    //Route::get('admin/home', [HomeController::class, 'adminHome'])->name('admin.home')->middleware('is_admin');







    route::get('/index2', [UserController::class, 'index2']);

    route::get('/index3', [UserController::class, 'index3']);

    route::get('/index4', [UserController::class, 'index4']);

    route::get('/index5', [UserController::class, 'index5']);



Auth::routes();



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('cron-activity-notification', [HomeApiController::class, 'cronSendFcmNotification'])->name('cron-activity-notification');

Route::get('/clear-cache', function() {
  // 
  Artisan::call('cache:clear');
  Artisan::call('optimize');
  Artisan::call('config:clear');
  Artisan::call('route:clear');
  Artisan::call('view:clear');
  return "Cache is cleared";
});

