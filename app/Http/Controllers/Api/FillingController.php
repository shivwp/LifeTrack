<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fillings;
use Validator;
use Auth;
use Carbon\Carbon;
class FillingController extends Controller
{
    public function addreview(Request $request){
      $todayDate = Carbon::now()->format('Y-m-d');
      $user_id=Auth::user()->id; 

      $validator = Validator::make($request->all(), [
        'review_massge'=>'required|string',
      ]);

      if ($validator->fails()) {
        return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
      } 

      $datacheck=Fillings::where('user_id',$user_id)->first();
          
      if(!empty($datacheck)){
        $datacheck->review_massge=$request->review_massge;
        $datacheck->save();
        return response()->json(['status' => true, 'message' =>"Fillings Added", 'data'=>$datacheck, ], 200);
      }else{
        $fillings=new Fillings;
        $fillings->user_id=$user_id;
        $fillings->review_massge=$request->review_massge;
        $fillings->review_date=$todayDate;
        $fillings->save();
       return response()->json(['status' => true, 'message' =>"Fillings updated", 'data'=>$fillings, ], 200);
      }
    }

    public function getfilling(Request $request){
      $user_id=Auth::user()->id; 
      $data = Fillings::where('user_id',$user_id)->first();
      return response()->json(['status' => true, 'message' =>"Fillings", 'data'=>$data, ], 200);
    }

}
