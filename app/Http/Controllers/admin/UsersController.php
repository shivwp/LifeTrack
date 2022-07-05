<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\Product;
use App\Models\listmanagement;
use App\Models\Journal_Management;
use Hash;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use DB;
use Mail;

class UsersController extends Controller
{   
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $title = "Users";

        $buton_name = "Add New";

        $users = User::all();

        return view('admin.users.index', compact('users','title','buton_name'));
    }
    public function index2()
    {
        abort_if(Gate::denies('vuser_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $title = "Users";
        $buton_name = "Add New";    
        $users = User::join('address', 'address.user_id', '=', 'users.id')->where('address.is_default', '=', '1')
                ->get();
        
        return view('admin.users.index2', compact('users','title','buton_name'));
    }


    public function create()
    {
        
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

         $title = "User Add";

         $roles = Role::all()->pluck('title', 'id');

         return view('admin.users.create', compact('roles','title'));
    }

    public function store(Request $request)
    {   

        if ($request->id) {
          $sms = "User details has been updated successfully" ;
        }else{
            
          $request->validate([
        'email' => 'required|unique:users',
        
      ]);
            $request->validate([
       'password' => 'required|min:8|string|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
        
      ]);
          $sms = "User has been registred successfully" ;
        }

        $password = Hash::make($request->password);
        
        if(!empty($request->password)){
            $password = Hash::make($request->password);
            $user = User::updateOrCreate(['id'=>$request->id],[

                                        'first_name'    => $request->first_name,
                                        'last_name'     => $request->last_name,
                                        'email'         => $request->email,
                                        'password'      => $password,
                                        'status'        => $request->status,
                                        'verfy_user'    => $request->verfy_user,
                                    ]);
        }else{
            $user = User::updateOrCreate(['id'=>$request->id],[

                                        'first_name'    => $request->first_name,
                                        'last_name'     => $request->last_name,
                                       
                                        'status'        => $request->status,
                                        'verfy_user'    => $request->verfy_user,
                                    ]);
        }
        $data=[

    		'first_name'=>$request->first_name,
            'last_name'     => $request->last_name,
            'email'         => $request->email,
    		
    		

    	];
    
      
            
        $user->roles()->sync($request->input('role'));

        return redirect()->route('dashboard.users.index')->with('message', ' '.$sms.' ');

    }

    public function edit($id)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

         $title = "User Edit";

         $roles = Role::all()->pluck('title', 'id');

         $user = User::findOrFail($id);

         $user->load('roles');

        return view('admin.users.create', compact('roles', 'user','title'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index');

    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles');

        return view('admin.users.show', compact('user'));
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = User::findOrFail($id);

        $user_role = DB::table('role_user')->where('user_id',$id)->delete();

        $user->delete();

        //$user_role->delete();

        return back()->with('message','User deleted successfully');;

    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);

    }

    public function usercount()
    {   
      
        $data= User::all()->count();
       return $data;
    }

    public function journalcount()
    {   
        $data =Journal_Management::all()->count();
        return $data;
    }
    public function listcount()
    {   
        $data = listmanagement::select('listmanagements.id')->count();
        return $data;
    }

    public function productcount ()
    {   
        $data = Product::select('Products.id')->count();
        return $data;
    }

    
}
