<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use Validate;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $data = Page::all();
        return view('admin.pages.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('admin.pages.create');
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
            'title'=>'required|unique:pages',
            'description'=>'required',
            'metatitle'=>'required',
            'metadescription'=>'required',
            'keyword'=>'required',
        ]);

        $data = new Page;
        $data->title=$request->title;
        $data->slug= \Str::slug($request->title);
        $data->description=$request->description;
        $data->metatitle=$request->metatitle;
        $data->metadescription=$request->metadescription;
        $data->keyword=$request->keyword;
        $data->save();

        return redirect()->route('dashboard.pages.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Page::findOrFail($id);
        return view('admin.pages.edit' , compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $data = $request->validate([
             'title'=>'required',
            'description'=>'required',
            'metatitle'=>'required',
            'metadescription'=>'required',
            'keyword'=>'required',
        ]);
    
        $data = Page::findOrFail($id);
        $data->title=$request->title;
        $data->slug= \Str::slug($request->title);
        $data->description=$request->description;
        $data->metatitle=$request->metatitle;
        $data->metadescription=$request->metadescription;
        $data->keyword=$request->keyword;
        $data->save();

        return redirect()->route('dashboard.pages.index');
    
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
         $data = Page::findOrFail($id);
        $data->delete();
        return back();
    }
}
