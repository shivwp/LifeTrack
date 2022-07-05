<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Tag;
use App\Models\Activity;
use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Validator;
use Carbon\Carbon;

class TagController extends Controller
{
  public function add_tag(Request $request)
  {


    $user_id = Auth::user()->id;
    $todayDate = Carbon::now()->format('Y-m-d');

    $validator = Validator::make($request->all(), [
      "tages"    => "required|array|min:1",
      "tages.*.tag_id"  => "required|numeric",
      "tages.*.date" => 'required|required|date|before:tomorrow|after_or_equal:' . $todayDate,
      "tages.*.budgeted_start_time" => 'required|date_format:H:i',
      "tages.*.budget_end_time" => 'required|date_format:H:i|after:budgeted_start_time',
      "tages.*.actual_start_time" => 'required|date_format:H:i',
      "tages.*.actual_end_time" => 'required|date_format:H:i|after:actual_start_time',

    ]);


    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }


    Tag::whereDate('date', $todayDate)->where('user_id', $user_id)->delete();
    $data = [];
    foreach ($request->tages as $key => $value) {
      //

      $data[$key]['user_id'] = $user_id;
      $data[$key]['tag_id'] = $value['tag_id'];
      $data[$key]['date'] = $value['date'];
      $data[$key]['budgeted_start_time'] = $value['budgeted_start_time'];
      $data[$key]['budget_end_time'] = $value['budget_end_time'];
      $data[$key]['actual_start_time'] = $value['actual_start_time'];
      $data[$key]['actual_end_time'] = $value['actual_end_time'];
    }

    Tag::insert($data);



    return response()->json(['status' => true, 'message' => "Add Bugedt Tag Activity successfully  ", 'activity' => $data], 200);
  }


  public function get_tag(Request $request)
  {
    $user_id = Auth::user()->id;
    $now = Carbon::now()->format('Y-m-d');
    $tages = Tag::select('tags.*', 'activitys.id')
      ->join('activitys', 'activitys.id', '=', 'tags.tag_id')
      ->where('date', $now)->where('user_id', $user_id)->where('date', $now)
      ->get();

    if (count($tages) == 0) {

      $today = Tag::where('date', '<', $now)->where('user_id', $user_id)->orderBy('date', 'desc')->first();

      if ($today) {
        $newdata = new Tag;
        $newdata->user_id = $user_id;
        $newdata->date = $now;
        $newdata->tag_id = $today->tag_id;
        $newdata->budgeted_start_time = isset($today->budgeted_start_time)?Carbon::parse($today->budgeted_start_time)->format('H'):'';
        $newdata->budget_end_time = isset($today->budget_end_time)?Carbon::parse($today->budget_end_time)->format('H'):'';
        $newdata->actual_start_time = isset($today->actual_start_time)?Carbon::parse($today->actual_start_time)->format('H'):'';
        $newdata->actual_end_time = isset($today->actual_end_time)?Carbon::parse($today->actual_end_time)->format('H'):''; //$today->actual_end_time;
        $newdata->save();
        


        $data = [
          "id" => $today->id,
          "user_id" => $today->user_id,
          "tag_id" => $today->tag_id,
          "date" => $today->date,
          "budgeted_start_time" => Carbon::parse($today->budgeted_start_time)->format('H:i'),
          "budget_end_time" => Carbon::parse($today->budget_end_time)->format('H:i'),
          "actual_start_time" => Carbon::parse($today->actual_start_time)->format('H:i'),
          "actual_end_time" => Carbon::parse($today->actual_end_time)->format('H:i'),
          "status" => $today->status,
          "created_at" => $today->created_at,
          "updated_at" => $today->updated_at
        ]; 
        return response()->json(['status' => true, 'message' => "Last Budget Activity Tag", 'data' => $data,], 200);
      } else {
        return response()->json(['status' => false, 'message' => "Not Budget Activity Tag Found", 'data' => $today,], 200);
      }
    }
    $bugeted = Tag::where('budgeted_start_time', '<>', NULL)->where('budget_end_time', '<>', NULL)->where('user_id', $user_id)->where('date', $now)->get();
    $data1["bugeted"] = $bugeted;

    $actual = Tag::where('user_id', $user_id)->where('date', $now)->get();
    $data1["actual"] = $actual;


    return response()->json(['status' => true, 'message' => "Get Budget Activity Tages Details", 'data' => $data1,], 200);
  }

  public function daywisedata(Request $request)
  {

    $validator = Validator::make($request->all(), [

      'user_id' => 'required|numeric',

    ]);


    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }
    $todayDate = Carbon::now()->format('Y-m-d');

    $test = Tag::where('user_id', $request->user_id)->where('date', $todayDate)->first();
    $today = Tag::where('user_id', $request->user_id)->where('date', $todayDate)->get();

    if ($test) {
      return response()->json(['status' => true, 'message' => "Toady  Bugedt Tag Activity Details", 'data' => $today,], 200);
    } else {

      return response()->json(['status' => true, 'message' => "Toady you have not do any Bugedt Tag Activity "], 200);
    }
  }


  public function weakwisedetals(Request $request)
  {

    $validator = Validator::make($request->all(), [

      'user_id' => 'required|numeric',

    ]);


    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }


    $test = Tag::where('user_id', $request->user_id)->whereBetween(
      'date',
      [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
    )->orderBy('date', 'desc')
      ->first();

    if ($test) {
      $weak = Tag::where('user_id', $request->user_id)->whereBetween(
        'date',
        [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
      )->orderBy('date', 'desc')
        ->get();
      return response()->json(['status' => true, 'message' => "Weak Bugedt Tag Activity Details", 'data' => $weak,], 200);
    } else {

      return response()->json(['status' => false, 'message' => "Weakely  Bugedt Tag Activity Details Available"], 200);
    }
  }

  public function monthlydetals(Request $request)
  {



    $validator = Validator::make($request->all(), [

      'user_id' => 'required|numeric',
    ]);


    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }



    $test = Tag::where('user_id', $request->user_id)->whereMonth('date', Carbon::now()->month)->first();

    if ($test) {
      $month = Tag::where('user_id', $request->user_id)->whereMonth('date', Carbon::now()->month)
        ->orderBy('date', 'asc')->get();
      return response()->json(['status' => true, 'message' => "Monthly Bugedt Tag Activity Details", 'data' => $month,], 200);
    } else {

      return response()->json(['status' => false, 'message' => "Monthly Bugedt Tag  Activity Details Available"], 200);
    }
  }
}
