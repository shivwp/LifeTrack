<?php



namespace App\Http\Controllers\admin;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\subscriptions;



use Auth;



class SubscriptionController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {

         

          $data = subscriptions::all();



        return view('admin.subscriptions.index',compact('data'));

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {

         return view('admin.subscriptions.create');

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

           

            'addfriends'=>'required',

            'creategroup'=>'required',

            'subscriptions_type'=>'required',

            'name'=>'required',

            'price'=>'required',

            

        ]);



         $roles=Auth::user()->roles->pluck('title')->first();



         // Auth::user()->role;

        $data = new subscriptions;

        $data->user_id= Auth::user()->id;

        $data->user_name=Auth::user()->first_name;

        $data->user_role=$roles;

        $data->addfriends=$request->addfriends;

        $data->creategroup=$request->creategroup;

        $data->subscriptions_type=$request->subscriptions_type;

        $data->name=$request->name;

        $data->price=$request->price;

        

        

        $data->save();



        return redirect()->route('dashboard.subscriptions.index');

    }



    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function show($id)

    {

       $user = subscriptions::findOrFail($id);

        return view('admin.subscriptions.edit' , compact('user'));

    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function edit(Request $request ,$id)

    {

          $data = $request->validate([

           

            'addfriends'=>'required',

            'creategroup'=>'required',

            'subscriptions_type'=>'required',

            'name'=>'required',

            'price'=>'required',

        ]);



         $roles=Auth::user()->roles->pluck('title')->first();



         

        $data =subscriptions::findOrFail($id);

        $data->user_id= Auth::user()->id;

        $data->user_name=Auth::user()->first_name;

        $data->user_role=$roles;

        $data->addfriends=$request->addfriends;

        $data->creategroup=$request->creategroup;

        $data->subscriptions_type=$request->subscriptions_type;

        $data->name=$request->name;

        $data->price=$request->price;

       

        $data->save();



        return redirect()->route('dashboard.subscriptions.index');

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

         $data = subscriptions::findOrFail($id);

        $data->delete();

        return back();

    }

}

