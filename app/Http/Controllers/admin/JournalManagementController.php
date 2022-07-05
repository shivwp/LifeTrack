<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Journal_Management;

class JournalManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $data = Journal_Management::all();
        return view('admin.journalmang.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.journalmang.create');
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
            'name'=>'required',
            'description'=>'required',
            'date'=>'required',
            'status'=>'required',
        ]);

        $data = new Journal_Management;
        $data->name=$request->name;
        $data->description=$request->description;
        $data->date=$request->date;
        $data->status=$request->status;
        $data->save();

        return redirect()->route('dashboard.journalmang.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $user = Journal_Management::findOrFail($id);
        return view('admin.journalmang.edit' , compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( Request $request, $id)
    {
        
         $data = $request->validate([
            'name'=>'required',
            'description'=>'required',
            'date'=>'required',
            'status'=>'required',
        ]);
        $data = Journal_Management::findOrFail($id);
        $data->name=$request->name;
        $data->description=$request->description;
        $data->date=$request->date;
        $data->status=$request->status;
        $data->save();

        return redirect()->route('dashboard.journalmang.index');
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
         $data = Journal_Management::findOrFail($id);
        $data->delete();
        return back();
    }
}
