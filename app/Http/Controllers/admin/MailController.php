<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MailTemplate;

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = MailTemplate::all();
        return view('admin.mails.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.mails.create');
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
            'form_name'=>'required',
            'subject'=>'required',
            'massage_category'=>'required',
            'form_email'=>'required|email',
            'reply_email'=>'required|email',
            'email_to'=>'required',
            'massage_content'=>'required',
        ]);

        $data = new MailTemplate;
        $data->form_name=$request->form_name;
        $data->subject= $request->subject;
        $data->massage_category=$request->massage_category;
        $data->form_email=$request->form_email;
        $data->reply_email=$request->reply_email;
        $data->email_to=$request->email_to;
         $data->massage_content=$request->massage_content;
        $data->save();

        return redirect()->route('dashboard.mails.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = MailTemplate::findOrFail($id);
        return view('admin.mails.edit' , compact('user'));
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
            'form_name'=>'required',
            'subject'=>'required',
            'massage_category'=>'required',
            'form_email'=>'required|email',
            'reply_email'=>'required|email',
           'email_to'=>'required',
            'massage_content'=>'required',
        ]);

        $data = MailTemplate::findOrFail($id);
        $data->form_name=$request->form_name;
        $data->subject= $request->subject;
        $data->massage_category=$request->massage_category;
        $data->form_email=$request->form_email;
        $data->reply_email=$request->reply_email;
        $data->email_to=$request->email_to;
         $data->massage_content=$request->massage_content;
        $data->save();


         return redirect()->route('dashboard.mails.index');
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
         $data = MailTemplate::findOrFail($id);
        $data->delete();
        return back();
    }
}
