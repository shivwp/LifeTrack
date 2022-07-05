<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Role;
use App\Models\Category;
use App\Models\User;
class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // $data = Activity::all();
        $data = Activity::Select('activitys.*','users.first_name','users.email')->join('users', 'users.id', '=', 'activitys.username')
               ->get();
               $userdetails = Activity::Select('activitys.*','users.first_name','users.email', 'users.id','activitys.activity')->join('users', 'users.id', '=', 'activitys.username')
               ->groupBy('users.id')->get();
    
        return view('admin.activity.index',compact('data','userdetails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
       $users =Role::where('title','User')->first()->users()->get();
     
       $category=Category::where('parent_id',0)->get();
       

       $sub_category=Category::where('parent_id','<>','id')->get();
       return view('admin.activity.create',compact('users','category','sub_category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

          $data = $request->validate([
           
            'activity'=>'required',
            
            'starttime' => 'date_format:H:i',
            'endtime' => 'date_format:H:i|after:starttime',
            'status'=>'required',
            'selectprivacy'=>'required',
            'selectcolor'=>'required',
            
           
           ]);

        $data = new Activity;
        $data->activity=$request->activity;
        $data->slug = \Str::slug($request->activity);
       
        $data->status=$request->status;
        $data->starttime=$request->starttime;
        $data->endtime=$request->endtime;
        $data->selectcolor=$request->selectcolor;
        $data->selectprivacy=$request->selectprivacy;
        $data->parent_id=$request->parent_id;
        $data->username=$request->username;
        $data->selectprivacy=$request->selectprivacy;
        $data->parent_catgory=$request->parent_catgory;
        $data->sub_category=$request->sub_category;
        
        
        $data->save();

        return redirect()->route('dashboard.activity.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Activity::findOrFail($id);
        $category=Category::where('parent_id',0)->get();
        
        $username =Role::where('title','User')->first()->users()->get();
        $sub_category=Category::where('parent_id','<>','0')->get();
        

        
        $data = Category::where('parent_id',$user->parent_catgory)->get();
      
        $usename=User::where('id',$user->username)->first();
        
        return view('admin.activity.edit',compact('user','username','category','sub_category','data','usename'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {

         $data = $request->validate([
           
            'activity'=>'required',
            'starttime' => 'date_format:H:i',
            'endtime' => 'date_format:H:i|after:starttime',
            'status'=>'required',
            'selectprivacy'=>'required',
            'selectcolor'=>'required',
           
           ]);

          $data = Activity::findOrFail($id);
          $data->activity=$request->activity;
        $data->slug = \Str::slug($request->activity);
       
        $data->starttime=$request->starttime;
        $data->endtime=$request->endtime;
        $data->selectcolor=$request->selectcolor;
        $data->selectprivacy=$request->selectprivacy;
        $data->parent_id=$request->parent_id;
        $data->selectprivacy=$request->selectprivacy;
        $data->status=$request->status;
        $data->username=$request->username;
        $data->parent_catgory=$request->parent_catgory;
        $data->sub_category=$request->sub_category;
        
        
        $data->save();

        return redirect()->route('dashboard.activity.index');
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Activity::findOrFail($id);
        $data->delete();
        return back();
    }


     public function getSubCategory(Request $request)
    {
         
        $parent_id = $request->cat_id;
         
        $subcategories = Category::where('parent_id',$parent_id)->get();
        return response()->json([
            'subcategories' => $subcategories
        ]);
    }
    
    public function serech(Request $request){
        //username
        $username=Activity::where('username','like',"%".$request->id."%")->where('username',$request->username)->get();
        $data=Activity::where('username','like',"%".$request->id."%")->where('username',$request->username)->Select('activitys.*','users.first_name','users.email')->join('users', 'users.id', '=', 'activitys.username')
               ->get();
        return view('admin.activity.showusers',compact('data'));
        
    }
    
}
