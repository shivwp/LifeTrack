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
use DB;
use App\Models\UserPrivacySettings;

class TagController extends Controller
{
  public function addActivities(Request $request)
  {

    $user_id = Auth::user()->id;
    $todayDate = Carbon::now()->format('Y-m-d');

    $validator = Validator::make($request->all(), [
      // "activitys"    => "required|array|min:1",
      "activitys.bugeted.*.tag_id"  => "required|numeric",
      "activitys.actual.*.tag_id"  => "required|numeric",
      "activitys.bugeted.*.date" => 'required|required|date|before:tomorrow|after_or_equal:' . $todayDate,
      "activitys.actual.*.date" => 'required|required|date|before:tomorrow|after_or_equal:' . $todayDate,
      // "activitys.*.budgeted_start_time" => 'required|date_format:H:i',
      // "activitys.*.budget_end_time" => 'required|date_format:H:i|after:budgeted_start_time',
      "activitys.bugeted.*.actual_start_time" => 'required|date_format:H:i',
      "activitys.bugeted.*.actual_end_time" => 'required|date_format:H:i|after:actual_start_time',

      "activitys.actual.*.actual_start_time" => 'required|date_format:H:i',
      "activitys.actual.*.actual_end_time" => 'required|date_format:H:i|after:actual_start_time',
    ]);

    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }

    DB::beginTransaction();

    try {

      Tag::whereDate('date', $todayDate)->where('user_id', $user_id)->delete();
      $data = [];

      $bugeted = $request->activitys['bugeted'];
      $actual = $request->activitys['actual'];

      $bugeted_count = count($bugeted);
      $actual_count = count($actual);

      $count = ($bugeted_count > $actual_count) ? $bugeted_count : $actual_count;

      $i = 0;
      while ($i < $count) {
        # code...
        if (isset($bugeted[$i]['id']) && isset($actual[$i]['id']) && $bugeted[$i]['id'] == $actual[$i]['id']) {

          $data[$i]['user_id'] = $user_id;
          $data[$i]['tag_id'] = $bugeted[$i]['tag_id'];
          $data[$i]['date'] = $bugeted[$i]['date'];

          $data[$i]['budgeted_start_time'] = $bugeted[$i]['budgeted_start_time'] ?? '';
          $data[$i]['budget_end_time'] = $bugeted[$i]['budget_end_time'] ?? '';
          $data[$i]['actual_start_time'] = $actual[$i]['actual_start_time'];
          $data[$i]['actual_end_time'] = $actual[$i]['actual_end_time'];

        } else {
          // if (isset($bugeted[$i]['tag_id'])) {
          if (isset($bugeted[$i])) {
            // 
            $data[$i]['user_id'] = $user_id;
            $data[$i]['tag_id'] = $bugeted[$i]['tag_id'];
            $data[$i]['date'] = $bugeted[$i]['date'];

            $data[$i]['budgeted_start_time'] = $bugeted[$i]['budgeted_start_time'] ?? '';
            $data[$i]['budget_end_time'] = $bugeted[$i]['budget_end_time'] ?? '';
            $data[$i]['actual_start_time'] = $bugeted[$i]['actual_start_time'];
            $data[$i]['actual_end_time'] = $bugeted[$i]['actual_end_time'];
          } else {
            // 
            $data[$i]['user_id'] = $user_id;
            $data[$i]['tag_id'] = $actual[$i]['tag_id'];
            $data[$i]['date'] = $actual[$i]['date'];

            $data[$i]['budgeted_start_time'] = '00:00';
            $data[$i]['budget_end_time'] = '00:00';
            $data[$i]['actual_start_time'] = $actual[$i]['actual_start_time'];
            $data[$i]['actual_end_time'] = $actual[$i]['actual_end_time'];
          }
        }
        $i++;
      }

      // foreach ($request->activitys as $key => $value) {
      //   //
      //   $data[$key]['user_id'] = $user_id;
      //   $data[$key]['tag_id'] = $value['tag_id'];
      //   $data[$key]['date'] = $value['date'];
      //   $data[$key]['budgeted_start_time'] = $value['budgeted_start_time'] ?? '';
      //   $data[$key]['budget_end_time'] = $value['budget_end_time'] ?? '';
      //   $data[$key]['actual_start_time'] = $value['actual_start_time'];
      //   $data[$key]['actual_end_time'] = $value['actual_end_time'];
      // }


      Tag::insert($data);

      DB::commit();

      $data1 = [];

      $now = Carbon::now()->format('Y-m-d');
      $bugeted = Tag::where('budgeted_start_time', '<>', '00:00')->where('budgeted_start_time', '<>', NULL)->where('budget_end_time', '<>', NULL)->where('user_id', $user_id)->where('date', $now)->get();
      foreach ($bugeted as $key => $value) {
        $value->budgeted_start_time = Carbon::parse($value->budgeted_start_time)->format('H:i');
        $value->budget_end_time = Carbon::parse($value->budget_end_time)->format('H:i');
        $value->actual_start_time = Carbon::parse($value->actual_start_time)->format('H:i');
        $value->actual_end_time = Carbon::parse($value->actual_end_time)->format('H:i');
        $value['tag'] = Activity::select('activity', 'parent_catgory', 'sub_category', 'starttime', 'endtime', 'selectcolor', 'selectprivacy')
          ->where('id', $value->tag_id)->first();

        unset($value['created_at']);
        unset($value['updated_at']);
      }
      $data1["bugeted"] = $bugeted;


      $actual = Tag::where('actual_start_time', '<>', '00:00')->where('user_id', $user_id)->where('date', $now)->get();

      foreach ($actual as $key => $value) {
        $value->budgeted_start_time = Carbon::parse($value->budgeted_start_time)->format('H:i');
        $value->budget_end_time = Carbon::parse($value->budget_end_time)->format('H:i');
        $value->actual_start_time = Carbon::parse($value->actual_start_time)->format('H:i');
        $value->actual_end_time = Carbon::parse($value->actual_end_time)->format('H:i');
        $value['tag'] = Activity::select('activity', 'parent_catgory', 'sub_category', 'starttime', 'endtime', 'selectcolor', 'selectprivacy')
          ->where('id', $value->tag_id)->first();
        unset($value['created_at']);
        unset($value['updated_at']);
      }
      $data1["actual"] = $actual;

      return response()->json(['status' => true, 'message' => "Activity updated successfully", 'activity' => $data1], 200);
      // 
    } catch (\Exception $e) {
      // 
      DB::rollback();

      return response()->json(['status' => false, 'message' => "Faild to add.", 'activity' => $e,], 200);
    }
  }


  public function get_tag(Request $request)
  {

    $user_id = Auth::user()->id;
    $now = Carbon::now()->format('Y-m-d');

    $tages = Tag::select('tags.*', 'activitys.id')
      ->join('activitys', 'activitys.id', '=', 'tags.tag_id')
      ->where('date', $now)->where('user_id', $user_id)
      ->get();

    if (count($tages) == 0) {

      $prev_date = Carbon::now()->subDays(1)->format('Y-m-d');

      $lastDate = Tag::where('tags.budgeted_start_time', '<>', NULL)->where('tags.budget_end_time', '<>', NULL)
        ->where('tags.budgeted_start_time', '<>', "00:00:00")->where('tags.budget_end_time', '<>', "00:00:00")
        ->whereDate('tags.date', '<=', $prev_date)->where('user_id', $user_id)
        ->orderBy('id', 'desc')
        ->first();
      if(empty($lastDate)){
        return response()->json(['status' => false, 'message' => "Not  Activity  Found", 'data' => [],], 200);
      }
      $tages = Tag::select('tags.*', 'activitys.id')
        ->join('activitys', 'activitys.id', '=', 'tags.tag_id')
        ->where('tags.budgeted_start_time', '<>', NULL)->where('tags.budget_end_time', '<>', NULL)
        ->where('tags.budgeted_start_time', '<>', "00:00:00")->where('tags.budget_end_time', '<>', "00:00:00")
        ->whereDate('tags.date', '=', $lastDate->date)->where('user_id', $user_id)
        ->get();

      if (empty($tages)) {
        return response()->json(['status' => false, 'message' => "Not  Activity  Found", 'data' => [],], 200);
      }

      $data = [];
      foreach ($tages as $key => $value) {
        # code
        $data[$key]['user_id'] = $user_id;
        $data[$key]['tag_id'] = $value->tag_id;
        $data[$key]['date'] = Carbon::now()->format('Y-m-d');
        $data[$key]['budgeted_start_time'] = $value->budgeted_start_time;
        $data[$key]['budget_end_time'] = $value->budget_end_time;
        $data[$key]['actual_start_time'] = $value->budgeted_start_time;
        $data[$key]['actual_end_time'] = $value->budget_end_time;
      }

      $st = Tag::insert($data);


      $today = Tag::where('date', '<', $now)->where('user_id', $user_id)->orderBy('date', 'desc')->first();

      if ($today) {

        //   $newdata = new Tag;
        //   $newdata->user_id = $user_id;
        //   $newdata->date = $now;
        //   $newdata->tag_id = $today->tag_id;
        //   $newdata->budgeted_start_time = isset($today->budgeted_start_time)?Carbon::parse($today->budgeted_start_time)->format('H'):'';
        //   $newdata->budget_end_time = isset($today->budget_end_time)?Carbon::parse($today->budget_end_time)->format('H'):'';
        //   $newdata->actual_start_time = isset($today->actual_start_time)?Carbon::parse($today->actual_start_time)->format('H'):'';
        //   $newdata->actual_end_time = isset($today->actual_end_time)?Carbon::parse($today->actual_end_time)->format('H'):''; //$today->actual_end_time;
        //   $newdata->save();

        //   $data = [
        //     "id" => $today->id,
        //     "user_id" => $today->user_id,
        //     "tag_id" => $today->tag_id,
        //     "date" => $today->date,
        //     "budgeted_start_time" => Carbon::parse($today->budgeted_start_time)->format('H:i'),
        //     "budget_end_time" => Carbon::parse($today->budget_end_time)->format('H:i'),
        //     "actual_start_time" => Carbon::parse($today->actual_start_time)->format('H:i'),
        //     "actual_end_time" => Carbon::parse($today->actual_end_time)->format('H:i'),
        //     "status" => $today->status,
        //     "created_at" => $today->created_at,
        //     "updated_at" => $today->updated_at
        //   ]; 

        $now = Carbon::now()->format('Y-m-d');

        $bugeted = Tag::where('budgeted_start_time', '<>', NULL)
          ->where('budget_end_time', '<>', NULL)
          ->where('user_id', $user_id)
          ->where('date', $now)
          ->get();

        foreach ($bugeted as $key => $value) {
          $value->budgeted_start_time = Carbon::parse($value->budgeted_start_time)->format('H:i');
          $value->budget_end_time = Carbon::parse($value->budget_end_time)->format('H:i');
          $value->actual_start_time = Carbon::parse($value->actual_start_time)->format('H:i');
          $value->actual_end_time = Carbon::parse($value->actual_end_time)->format('H:i');
          $value['tag'] = Activity::select('activity', 'parent_catgory', 'sub_category', 'starttime', 'endtime', 'selectcolor', 'selectprivacy')
            ->where('id', $value->tag_id)->first();
          unset($value['created_at']);
          unset($value['updated_at']);
        }

        $data1["bugeted"] = $bugeted;

        $actual = Tag::where('user_id', $user_id)->where('date', $now)->get();

        foreach ($actual as $key => $value) {
          $value->budgeted_start_time = Carbon::parse($value->budgeted_start_time)->format('H:i');
          $value->budget_end_time = Carbon::parse($value->budget_end_time)->format('H:i');
          $value->actual_start_time = Carbon::parse($value->actual_start_time)->format('H:i');
          $value->actual_end_time = Carbon::parse($value->actual_end_time)->format('H:i');
          $value['tag'] = Activity::select('activity', 'parent_catgory', 'sub_category', 'starttime', 'endtime', 'selectcolor', 'selectprivacy')
            ->where('id', $value->tag_id)->first();
          unset($value['created_at']);
          unset($value['updated_at']);
        }
        $data1["actual"] = $actual;

        return response()->json(['status' => true, 'message' => "Last Activity", 'data' => $data1,], 200);
        //
      } else {

        return response()->json(['status' => false, 'message' => "Not  Activity  Found", 'data' => $today,], 200);
      }
    }

    $bugeted = Tag::where('budgeted_start_time', '<>', NULL)->where('budget_end_time', '<>', NULL)->where('user_id', $user_id)->where('date', $now)->get();
    foreach ($bugeted as $key => $value) {
      $value->budgeted_start_time = Carbon::parse($value->budgeted_start_time)->format('H:i');
      $value->budget_end_time = Carbon::parse($value->budget_end_time)->format('H:i');
      $value->actual_start_time = Carbon::parse($value->actual_start_time)->format('H:i');
      $value->actual_end_time = Carbon::parse($value->actual_end_time)->format('H:i');
      $value['tag'] = Activity::select('activity', 'parent_catgory', 'sub_category', 'starttime', 'endtime', 'selectcolor', 'selectprivacy')
        ->where('id', $value->tag_id)->first();

      unset($value['created_at']);
      unset($value['updated_at']);
    }
    $data1["bugeted"] = $bugeted;

    $actual = Tag::where('user_id', $user_id)->where('date', $now)->get();

    foreach ($actual as $key => $value) {
      $value->budgeted_start_time = Carbon::parse($value->budgeted_start_time)->format('H:i');
      $value->budget_end_time = Carbon::parse($value->budget_end_time)->format('H:i');
      $value->actual_start_time = Carbon::parse($value->actual_start_time)->format('H:i');
      $value->actual_end_time = Carbon::parse($value->actual_end_time)->format('H:i');
      $value['tag'] = Activity::select('activity', 'parent_catgory', 'sub_category', 'starttime', 'endtime', 'selectcolor', 'selectprivacy')
        ->where('id', $value->tag_id)->first();
      unset($value['created_at']);
      unset($value['updated_at']);
    }
    $data1["actual"] = $actual;

    return response()->json(['status' => true, 'message' => "Get Activity Details", 'data' => $data1,], 200);
  }

  public function dailyActivityReport(Request $request)
  {
    $user_id = Auth::user()->id;
    $validator = Validator::make($request->all(), [
      'user_id' => 'numeric',
    ]);
    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }

    $activities = $request->activities;
    if(isset($activities)) {
      $activities = explode(',', $activities);
    }

    $todayDate = Carbon::now()->format('Y-m-d');
    $public = false; 
    if(isset($request->user_id)) {
      $user_id = $request->user_id;
      $public = true; 
    }

    $test = Tag::where('user_id', $user_id)->where('date', $todayDate)->first();
    $actual_data = [];
    $budgeted_data = [];
    $bar_chat = [];
    
    if (!$test) {
      return response()->json(['status' => false, 'message' => "Toady you have not do any Budget Tag Activity ", 'data' => null], 200);
    } else {
      // 
      $data = [];
      $q = Tag::join('activitys','activitys.id','=','tags.tag_id')->where('budgeted_start_time', '<>', '00:00')->where('tags.budgeted_start_time', '<>', NULL)->where('tags.budget_end_time', '<>', NULL)->where('tags.user_id', $user_id)->where('tags.date', $todayDate)->select('tags.*','activitys.activity', 'activitys.selectcolor');
      if(isset($activities)) {
        $q->whereIn('tag_id', $activities);
      }

      if(isset($request->category)) {
        $q->where('activitys.parent_catgory', $request->category);
      }

      if(isset($request->sub_category)) {
        $q->where('activitys.sub_category', $request->sub_category);
      }

      if($public) {
        $q->where('activitys.selectprivacy', '1');
      }

      $today = $q->get();
      $totalmins = 24*60; 

      $numb = 0; 
      foreach($today as $key => $value) {
        $budgeted_time = (double)((((new Carbon($value->budgeted_start_time))->diffInMinutes(new Carbon($value->budget_end_time)))/$totalmins)*100);
        $budgeted_data[] = [
          'title' => $value->activity,
          'time' => number_format($budgeted_time,2,'.',''),
          'color' => ($value->selectcolor)?$value->selectcolor:''
        ];  
        $numb = $numb+number_format($budgeted_time,2,'.','');
      }
      $budgeted_data[] = [
        'title' => 'Remain',
        'time' => number_format(100-$numb,2,'.',''),
        'color' => '0xffffffff'
      ]; 

      $data['budgeted_data'] = $budgeted_data;

      $q = Tag::join('activitys','activitys.id','=','tags.tag_id')->where('tags.user_id', $user_id)->where('tags.date', $todayDate)->select('tags.*','activitys.activity', 'activitys.selectcolor');
      if(isset($activities)) {
        $q->whereIn('tag_id', $activities);
      }
      if(isset($request->category)) {
        $q->where('activitys.parent_catgory', $request->category);
      }
      if(isset($request->sub_category)) {
        $q->where('activitys.sub_category', $request->sub_category);
      }
      if($public) {
        $q->where('activitys.selectprivacy', '1');
      }
      $today = $q->get();
      $numb = 0;
      $numb2 = 0;
      foreach($today as $key => $value) {
        $actual_time = (double)((((new Carbon($value->actual_start_time))->diffInMinutes(new Carbon($value->actual_end_time)))/$totalmins)*100);
        $actual_data[] = [
          'title' => $value->activity,
          'time' => number_format($actual_time,2,'.',''),
          'color' => ($value->selectcolor)?$value->selectcolor:''
        ];
        
        $budgeted_time = (double)((((new Carbon($value->budgeted_start_time))->diffInMinutes(new Carbon($value->budget_end_time)))/$totalmins)*100);
        if($value->budgeted_start_time == '' || $value->budgeted_start_time == '00:00') {
          $budgeted_time = 0;
        }
        $bar_chat[] = [
          'title' => $value->activity,
          'budgeted_time' => number_format($budgeted_time,2,'.',''),
          'actual_time' => number_format($actual_time,2,'.',''),
          'color' => ($value->selectcolor)?$value->selectcolor:''
        ];

        $numb = $numb + number_format($actual_time,2,'.','');
        $numb2 = $numb2 + number_format($budgeted_time,2,'.','');
      }

      $actual_data[] = [
        'title' => 'Remaing',
        'time' => number_format((100-$numb),2,'.',''),
        'color' => '0xffffffff'
      ];
      // $bar_chat[] = [
      //   'title' => 'Remaing',
      //   'budgeted_time' => number_format((100-$numb2),2,'.',''),
      //   'actual_time' => number_format((100-$numb),2,'.',''),
      //   'color' => '0xffffffff'
      // ];

      $data['actual_data'] = $actual_data;
      $data['bar_chat'] = $bar_chat;
      
      // if(isset($request->user_id)) {
      //   $user_id = $request->user_id;
      //   $settings = UserPrivacySettings::where('user_id', $user_id)->first();
      //   $privacy_setting = [
      //       'profile_photo' => (isset($settings->profile_photo)&& $settings->profile_photo)?true:false,
      //       'mood_update' => (isset($settings->mood_update)&& $settings->mood_update)?true:false,
      //       'charts' => (isset($settings->charts)&& $settings->charts)?true:false,
      //       'budget_actual' => (isset($settings->budget_actual)&& $settings->budget_actual)?true:false,
      //       'chart_name' => (isset($settings->chart_name)&& $settings->chart_name)?true:false,
      //   ]; 
      //   $data['privacy_setting'] = (object)$privacy_setting; 
      // } else {
      //   $data['privacy_setting'] = null; 
      // } 
      


      return response()->json(['status' => true, 'message' => "Toady Budget Tag Activity Details", 'data' => $data,], 200);
    }
  }

  public function weeklyActivityReport(Request $request)
  {
    $user_id = Auth::user()->id;
    $validator = Validator::make($request->all(), [
      'user_id' => 'numeric',
    ]);
    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }

    $public = false; 
    if(isset($request->user_id)) {
      $user_id = $request->user_id;
      $public = true; 
    }

    $test = Tag::where('user_id', $user_id)
      ->whereBetween('date',[Carbon::now()->startOfWeek()->format('Y-m-d'), Carbon::now()->format('Y-m-d')])
      ->orderBy('date', 'desc')
      ->first();

    $actual_data = [];
    $budgeted_data = [];
    $bar_chat = [];
    $data = [];

    $activities = $request->activities;
    if(isset($activities)) {
      $activities = explode(',', $activities);
    }

    if (!$test) {
      return response()->json(['status' => false, 'message' => "Weakely Budget Tag Activity Details Not Available", 'data' => null], 200);
    } else {
      // 
      // $today = Tag::join('activitys','activitys.id','=','tags.tag_id')->where('budgeted_start_time', '<>', '00:00')->where('tags.budgeted_start_time', '<>', NULL)->where('tags.budget_end_time', '<>', NULL)->where('tags.user_id', $user_id)->where('tags.date', $todayDate)->select('tags.*','activitys.activity')->get();
      $totalmins = 24*60; 
      $numb = 0; 
      $q = Tag::join('activitys','activitys.id','=','tags.tag_id')
        ->where('tags.user_id',$user_id)
        ->whereBetween('tags.date', [Carbon::now()->startOfWeek(), Carbon::now()->format('Y-m-d')])
        ->where('budgeted_start_time', '<>', '00:00')
        ->where('tags.budgeted_start_time', '<>', NULL)
        ->where('tags.budget_end_time', '<>', NULL)
        ->orderBy('tags.date', 'desc')
        ->select('tags.*', 'activitys.activity', 'activitys.selectcolor');
        

      if(isset($request->activities)) {
        $q->whereIn('tags.tag_id', $activities);
      }

      if(isset($request->category)) {
        $q->where('activitys.parent_catgory', $request->category);
      }

      if(isset($request->sub_category)) {
        $q->where('activitys.sub_category', $request->sub_category);
      }
      if($public) {
        $q->where('activitys.selectprivacy', '1');
      }
      // if(isset($activities)) {
      //   $q->whereIn('tag_id', $activities);
      // }

      $budgeted_weak = $q->get();
      $key = 0;
      foreach($budgeted_weak as $key => $value) {
        // 
        $budgeted_time = number_format((float)((new Carbon($value->budgeted_start_time))->diffInMinutes(new Carbon($value->budget_end_time))),2,'.','');
        if(array_key_exists($value->activity, $budgeted_data)) {
          $budgeted_data[$value->activity]['time'] = $budgeted_data[$value->activity]['time'] + number_format((float)$budgeted_time,2,'.','');
        } else {
          $budgeted_data[$value->activity]['time'] = number_format((float)$budgeted_time,2,'.','');
          $budgeted_data[$value->activity]['color'] = ($value->selectcolor)?$value->selectcolor:'';
        }
      }
      $totalmins = $totalmins * ($key+1); 

      $budgeted_records = [];
      foreach ($budgeted_data as $k => $val) {
        # code...
        $budgeted_records[] = [
          'title' =>$k,
          'time' => number_format(((((float)$val['time'])/$totalmins)*100),2,'.',''),
          'color' => $val['color']
        ];
        $numb = $numb + number_format(((((float)$val['time'])/$totalmins)*100),2,'.','');
      }
      $budgeted_records[] = [
        'title' =>'Remaing',
        'time' => number_format((100-$numb),2,'.',''),
        'color' => '0xffffffff'
      ];

      $data['budgeted_data'] = $budgeted_records;

      $q = Tag::join('activitys','activitys.id','=','tags.tag_id')
        ->where('tags.user_id',$user_id)
        ->whereBetween('tags.date', [Carbon::now()->startOfWeek(), Carbon::now()->format('Y-m-d')])
        ->orderBy('tags.date', 'desc')
        ->select('tags.*','activitys.activity','activitys.selectcolor');

      if(isset($request->activities)) {
        $q->whereIn('tags.tag_id', $activities);
      }

      if(isset($request->category)) {
        $q->where('activitys.parent_catgory', $request->category);
      }

      if(isset($request->sub_category)) {
        $q->where('activitys.sub_category', $request->sub_category);
      }
      if($public) {
        $q->where('activitys.selectprivacy', '1');
      }
      $actual_weak = $q->get();
      $key = 0;
      foreach($actual_weak as $key => $value) {

        $actual_time = number_format((float)((new Carbon($value->actual_start_time))->diffInMinutes(new Carbon($value->actual_end_time))),2,'.','');
        if(array_key_exists($value->activity, $actual_data)) {
          $actual_data[$value->activity]['time'] = $actual_data[$value->activity]['time'] + number_format((float)$actual_time,2,'.','');
        } else {
          $actual_data[$value->activity]['time'] = number_format((float)$actual_time,2,'.','');
          $actual_data[$value->activity]['color'] = ($value->selectcolor)?$value->selectcolor:'';
        }

        $budgeted_time = (double)((new Carbon($value->budgeted_start_time))->diffInMinutes(new Carbon($value->budget_end_time)));
        if($value->budgeted_start_time == '' || $value->budgeted_start_time == '00:00') {
          $budgeted_time = 0;
        }
        if(array_key_exists($value->activity, $bar_chat)) {
          $bar_chat[$value->activity]['budgeted_time'] = $bar_chat[$value->activity]['budgeted_time'] + number_format((float)$budgeted_time,2,'.','');
          $bar_chat[$value->activity]['actual_time'] = $bar_chat[$value->activity]['actual_time'] + number_format((float)$actual_time,2,'.','');
        } else {
          $bar_chat[$value->activity]['budgeted_time'] = number_format((float)$budgeted_time,2,'.','');
          $bar_chat[$value->activity]['actual_time'] = number_format((float)$actual_time,2,'.','');
          $bar_chat[$value->activity]['color'] = ($value->selectcolor)?$value->selectcolor:'';
        }

      }

      $totalmins = $totalmins * ($key + 1);
      $numb2 = 0;
      $actual_records = [];
      foreach ($actual_data as $k => $val) {
        # code...
        $actual_records[] = [
          'title' =>$k,
          'time' => number_format(((((float)$val['time'])/$totalmins)*100),2,'.',''), 
          'color' => $val['color']
        ];
        $numb2 = $numb2+number_format(((((float)$val['time'])/$totalmins)*100),2,'.','');
      }
      $actual_records[] = [
        'title' =>'Remaing',
        'time' => number_format((100-$numb2),2,'.',''), 
        'color' => '0xffffffff'
      ];
      $data['actual_data'] = $actual_records;

      $bar_chat_records = [];
      $numb = 0;
      $numb2 = 0;
      foreach ($bar_chat as $k => $val) {
        # code...
        $bar_chat_records[] = [
          'title' => $k,
          'budgeted_time' => number_format(((((float)$bar_chat[$k]['budgeted_time'])/$totalmins)*100),2,'.',''),
          'actual_time' => number_format(((((float)$bar_chat[$k]['actual_time'])/$totalmins)*100),2,'.',''),
          'color' => $bar_chat[$k]['color'],
        ];
        $numb = $numb+number_format(((((float)$bar_chat[$k]['budgeted_time'])/$totalmins)*100),2,'.','');
        $numb2 = $numb2+number_format(((((float)$bar_chat[$k]['actual_time'])/$totalmins)*100),2,'.','');
      }
      // $bar_chat_records[] = [
      //   'title' =>'Remaing',
      //   'budgeted_time' => number_format((100-$numb),2,'.',''),
      //   'actual_time' => number_format((100-$numb2),2,'.',''), 
      //   'color' => '0xffffffff'
      // ];
      $data['bar_chat'] = $bar_chat_records;
     
      return response()->json(['status' => true, 'message' => "Weakely Budget Tag Activity Details", 'data' => $data,], 200);
    }
  }

  public function monthlyActivityReport(Request $request)
  {
    $user_id = Auth::user()->id;
    $validator = Validator::make($request->all(), [
      'user_id' => 'numeric',
    ]);
    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }
    $public = false; 
    if(isset($request->user_id)) {
      $user_id = $request->user_id;
      $public = true;
    }

    $activities = $request->activities;
    if(isset($activities)) {
      $activities = explode(',', $activities);
    }

    $test = Tag::where('user_id', $user_id)
      ->whereBetween('date',[Carbon::now()->startOfMonth()->format('Y-m-d'), Carbon::now()->format('Y-m-d')])
      ->orderBy('date', 'desc')
      ->first();

    $actual_data = [];
    $budgeted_data = [];
    $bar_chat = [];
    $data = [];
    if (!$test) {
      return response()->json(['status' => false, 'message' => "Monthly Budget Tag Activity Details Not Available", 'data' => null], 200);
    } else {
      // 

      // $today = Tag::join('activitys','activitys.id','=','tags.tag_id')->where('budgeted_start_time', '<>', '00:00')->where('tags.budgeted_start_time', '<>', NULL)->where('tags.budget_end_time', '<>', NULL)->where('tags.user_id', $user_id)->where('tags.date', $todayDate)->select('tags.*','activitys.activity')->get();
      $totalmins = 24*60;

      $q = Tag::join('activitys','activitys.id','=','tags.tag_id')
        ->where('tags.user_id',$user_id)
        ->whereBetween('tags.date', [Carbon::now()->startOfMonth(), Carbon::now()->format('Y-m-d')])
        ->where('budgeted_start_time', '<>', '00:00')
        ->where('tags.budgeted_start_time', '<>', NULL)
        ->where('tags.budget_end_time', '<>', NULL)
        ->orderBy('tags.date', 'desc')
        ->select('tags.*','activitys.activity','activitys.selectcolor');

      if(isset($request->activities)) {
        $q->whereIn('tags.tag_id', $activities);
      }

      if(isset($request->category)) {
        $q->where('activitys.parent_catgory', $request->category);
      }

      if(isset($request->sub_category)) {
        $q->where('activitys.sub_category', $request->sub_category);
      }
      if($public) {
        $q->where('activitys.selectprivacy', '1');
      }
      $budgeted_weak = $q->get();
      $numb = 0;
      $key = 0;
      foreach($budgeted_weak as $key => $value) {

        $budgeted_time = number_format((float)((new Carbon($value->budgeted_start_time))->diffInMinutes(new Carbon($value->budget_end_time))),2,'.','');
        if(array_key_exists($value->activity, $budgeted_data)) {
          $budgeted_data[$value->activity]['time'] = $budgeted_data[$value->activity]['time'] + number_format((float)$budgeted_time,2,'.','');
        } else {
          $budgeted_data[$value->activity]['time'] = number_format((float)$budgeted_time,2,'.','');
          $budgeted_data[$value->activity]['color'] = ($value->selectcolor)?$value->selectcolor:'';
        }
      }
      $totalmins = $totalmins * ($key + 1);

      $budgeted_records = [];
      foreach ($budgeted_data as $k => $val) {
        # code...
        $budgeted_records[] = [
          'title' =>$k,
          'time' => number_format(((((float)$val['time'])/$totalmins)*100),2,'.',''), 
          'color' => $val['color']
        ];
        $numb = $numb + number_format(((((float)$val['time'])/$totalmins)*100),2,'.',''); 
      }
      $budgeted_records[] = [
        'title' => 'Remaing',
        'time' => number_format((100-$numb),2,'.',''), 
        'color' => '0xffffffff'
      ];
      $data['budgeted_data'] = $budgeted_records;

      $q = Tag::join('activitys','activitys.id','=','tags.tag_id')
        ->where('tags.user_id',$user_id)
        ->whereBetween('tags.date', [Carbon::now()->startOfMonth(), Carbon::now()->format('Y-m-d')])
        ->orderBy('tags.date', 'desc')
        ->select('tags.*','activitys.activity','activitys.selectcolor');

      if(isset($request->activities)) {
        $q->whereIn('tags.tag_id', $activities);
      }

      if(isset($request->category)) {
        $q->where('activitys.parent_catgory', $request->category);
      }

      if(isset($request->sub_category)) {
        $q->where('activitys.sub_category', $request->sub_category);
      }

      if($public) {
        $q->where('activitys.selectprivacy', '1');
      }

      $actual_weak = $q->get();
      $key = 0; 
      foreach($actual_weak as $key => $value) {

        $actual_time = number_format((float)((new Carbon($value->actual_start_time))->diffInMinutes(new Carbon($value->actual_end_time))),2,'.','');
        if(array_key_exists($value->activity, $actual_data)) {
          $actual_data[$value->activity]['time'] = $actual_data[$value->activity]['time'] + number_format((float)$actual_time,2,'.','');
        } else {
          $actual_data[$value->activity]['time'] = number_format((float)$actual_time,2,'.','');
          $actual_data[$value->activity]['color'] = ($value->selectcolor)?$value->selectcolor:'';
        }

        $budgeted_time = (double)((new Carbon($value->budgeted_start_time))->diffInMinutes(new Carbon($value->budget_end_time)));
        if($value->budgeted_start_time == '' || $value->budgeted_start_time == '00:00') {
          $budgeted_time = 0;
        }
        if(array_key_exists($value->activity, $bar_chat)) {
          $bar_chat[$value->activity]['budgeted_time'] = $bar_chat[$value->activity]['budgeted_time'] + number_format((float)$budgeted_time,2,'.','');
          $bar_chat[$value->activity]['actual_time'] = $bar_chat[$value->activity]['actual_time'] + number_format((float)$actual_time,2,'.','');
        } else {
          $bar_chat[$value->activity]['budgeted_time'] = number_format((float)$budgeted_time,2,'.','');
          $bar_chat[$value->activity]['actual_time'] = number_format((float)$actual_time,2,'.','');
          $bar_chat[$value->activity]['color'] = ($value->selectcolor)?$value->selectcolor:'';;
        }

      }
      $totalmins = $totalmins * ($key + 1);
      $numb = 0;
      $actual_records = [];
      foreach ($actual_data as $k => $val) {
        # code...
        $actual_records[] = [
          'title' =>$k,
          'time' => number_format(((((float)$val['time'])/$totalmins)*100),2,'.',''),
          'color' => $val['color']
        ];
        $numb = $numb + number_format(((((float)$val['time'])/$totalmins)*100),2,'.','');
      }
      $actual_records[] = [
        'title' => 'Remaing',
        'time' => number_format((100-$numb),2,'.',''),
        'color' => '0xffffffff'
      ];
      $data['actual_data'] = $actual_records;

      $bar_chat_records = [];
      $numb = 0; 
      $numb2 = 0; 
      // 
      foreach ($bar_chat as $k => $val) {
        # code...
        $bar_chat_records[] = [
          'title' => $k,
          'budgeted_time' => number_format(((((float)$bar_chat[$k]['budgeted_time'])/$totalmins)*100),2,'.',''),
          'actual_time' => number_format(((((float)$bar_chat[$k]['actual_time'])/$totalmins)*100),2,'.',''),
          'color' => $bar_chat[$k]['color'],
        ];

        $numb = $numb + number_format(((((float)$bar_chat[$k]['budgeted_time'])/$totalmins)*100),2,'.',''); 
        $numb2 = $numb2 + number_format(((((float)$bar_chat[$k]['actual_time'])/$totalmins)*100),2,'.',''); 
      }
      // $bar_chat_records[] = [
      //   'title' => 'Remaing',
      //   'budgeted_time' => number_format((100-$numb),2,'.',''),
      //   'actual_time' => number_format((100-$numb2),2,'.',''),
      //   'color' => '0xffffffff',
      // ];
      $data['bar_chat'] = $bar_chat_records;
      
      return response()->json(['status' => true, 'message' => "Monthly Budget Tag Activity Details", 'data' => $data,], 200);
    }
  }

  public function customActivityReport(Request $request)
  {
    $user_id = Auth::user()->id;
    $validator = Validator::make($request->all(), [
      'user_id' => 'numeric',
    ]);
    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }

    $public = false;
    if(isset($request->user_id)) {
      $user_id = $request->user_id;
      $public = true;
    }

    $activities = $request->activities;
    if(isset($activities)) {
      $activities = explode(',', $activities);
    }
    $start_date = isset($request->start_date)?$request->start_date:Carbon::now()->startOfWeek()->format('Y-m-d');
    $end_date = isset($request->end_date)?$request->end_date:Carbon::now()->format('Y-m-d');

    $test = Tag::where('user_id', $user_id)
      ->whereBetween('date',[$start_date, $end_date])
      ->orderBy('date', 'desc')
      ->first();

    $actual_data = [];
    $budgeted_data = [];
    $bar_chat = [];
    $data = [];
    if (!$test) {
      return response()->json(['status' => false, 'message' => "Budget Tag Activity Details Not Available", 'data' => null], 200);
    } else {
      // 
      $totalmins = 24*60;

      $q = Tag::join('activitys','activitys.id','=','tags.tag_id')
        ->where('tags.user_id',$user_id)
        ->whereBetween('tags.date', [$start_date, $end_date])
        ->where('budgeted_start_time', '<>', '00:00')
        ->where('tags.budgeted_start_time', '<>', NULL)
        ->where('tags.budget_end_time', '<>', NULL)
        ->orderBy('tags.date', 'desc')
        ->select('tags.*','activitys.activity','activitys.selectcolor');

      if(isset($request->activities)) {
        $q->whereIn('tags.tag_id', $activities);
      }

      if(isset($request->category)) {
        $q->where('activitys.parent_catgory', $request->category);
      }

      if(isset($request->sub_category)) {
        $q->where('activitys.sub_category', $request->sub_category);
      }

      if($public) {
        $q->where('activitys.selectprivacy', '1');
      }

      $budgeted_weak = $q->get();
      $numb = 0;
      $key = 0; 
      foreach($budgeted_weak as $key => $value) {

        $budgeted_time = number_format((float)((new Carbon($value->budgeted_start_time))->diffInMinutes(new Carbon($value->budget_end_time))),2,'.','');
        if(array_key_exists($value->activity, $budgeted_data)) {
          $budgeted_data[$value->activity]['time'] = $budgeted_data[$value->activity]['time'] + number_format((float)$budgeted_time,2,'.','');
        } else {
          $budgeted_data[$value->activity]['time'] = number_format((float)$budgeted_time,2,'.','');
          $budgeted_data[$value->activity]['color'] = ($value->selectcolor)?$value->selectcolor:'';
        }
      }
      $totalmins = $totalmins * ($key + 1);

      $budgeted_records = [];
      foreach ($budgeted_data as $k => $val) {
        # code...
        $budgeted_records[] = [
          'title' =>$k,
          'time' => number_format(((((float)$val['time'])/$totalmins)*100),2,'.',''), 
          'color' => $val['color']
        ];
        $numb = $numb + number_format(((((float)$val['time'])/$totalmins)*100),2,'.',''); 
      }
      $budgeted_records[] = [
        'title' => 'Remaing',
        'time' => number_format((100-$numb),2,'.',''), 
        'color' => '0xffffffff'
      ];
      $data['budgeted_data'] = $budgeted_records;

      $q = Tag::join('activitys','activitys.id','=','tags.tag_id')
        ->where('tags.user_id',$user_id)
        ->whereBetween('tags.date', [$start_date, $end_date])
        ->orderBy('tags.date', 'desc')
        ->select('tags.*','activitys.activity','activitys.selectcolor');

      if(isset($request->activities)) {
        $q->whereIn('tags.tag_id', $activities);
      }

      if(isset($request->category)) {
        $q->where('activitys.parent_catgory', $request->category);
      }

      if(isset($request->sub_category)) {
        $q->where('activitys.sub_category', $request->sub_category);
      }
      if($public) {
        $q->where('activitys.selectprivacy', '1');
      }
      $actual_weak = $q->get();
      $key = 0; 
      foreach($actual_weak as $key => $value) {

        $actual_time = number_format((float)((new Carbon($value->actual_start_time))->diffInMinutes(new Carbon($value->actual_end_time))),2,'.','');
        if(array_key_exists($value->activity, $actual_data)) {
          $actual_data[$value->activity]['time'] = $actual_data[$value->activity]['time'] + number_format((float)$actual_time,2,'.','');
        } else {
          $actual_data[$value->activity]['time'] = number_format((float)$actual_time,2,'.','');
          $actual_data[$value->activity]['color'] = ($value->selectcolor)?$value->selectcolor:'';
        }

        $budgeted_time = (double)((new Carbon($value->budgeted_start_time))->diffInMinutes(new Carbon($value->budget_end_time)));
        if($value->budgeted_start_time == '' || $value->budgeted_start_time == '00:00') {
          $budgeted_time = 0;
        }
        if(array_key_exists($value->activity, $bar_chat)) {
          $bar_chat[$value->activity]['budgeted_time'] = $bar_chat[$value->activity]['budgeted_time'] + number_format((float)$budgeted_time,2,'.','');
          $bar_chat[$value->activity]['actual_time'] = $bar_chat[$value->activity]['actual_time'] + number_format((float)$actual_time,2,'.','');
        } else {
          $bar_chat[$value->activity]['budgeted_time'] = number_format((float)$budgeted_time,2,'.','');
          $bar_chat[$value->activity]['actual_time'] = number_format((float)$actual_time,2,'.','');
          $bar_chat[$value->activity]['color'] = ($value->selectcolor)?$value->selectcolor:'';;
        }
      }
      $totalmins = $totalmins * ($key + 1);
      $numb = 0;
      $actual_records = [];
      foreach ($actual_data as $k => $val) {
        # code...
        $actual_records[] = [
          'title' =>$k,
          'time' => number_format(((((float)$val['time'])/$totalmins)*100),2,'.',''),
          'color' => $val['color']
        ];
        $numb = $numb + number_format(((((float)$val['time'])/$totalmins)*100),2,'.','');
      }
      $actual_records[] = [
        'title' => 'Remaing',
        'time' => number_format((100-$numb),2,'.',''),
        'color' => '0xffffffff'
      ];
      $data['actual_data'] = $actual_records;

      $bar_chat_records = [];
      $numb = 0; 
      $numb2 = 0; 
      // 
      foreach ($bar_chat as $k => $val) {
        # code...
        $bar_chat_records[] = [
          'title' => $k,
          'budgeted_time' => number_format(((((float)$bar_chat[$k]['budgeted_time'])/$totalmins)*100),2,'.',''),
          'actual_time' => number_format(((((float)$bar_chat[$k]['actual_time'])/$totalmins)*100),2,'.',''),
          'color' => $bar_chat[$k]['color'],
        ];

        $numb = $numb + number_format(((((float)$bar_chat[$k]['budgeted_time'])/$totalmins)*100),2,'.',''); 
        $numb2 = $numb2 + number_format(((((float)$bar_chat[$k]['actual_time'])/$totalmins)*100),2,'.',''); 
      }

      $data['bar_chat'] = $bar_chat_records;
      
      return response()->json(['status' => true, 'message' => "Tag Activity Details", 'data' => $data,], 200);
    }
  }

  public function categoryBasedActivityReport(Request $request)
  {
    $user_id = Auth::user()->id;
    $validator = Validator::make($request->all(), [
      'user_id' => 'numeric',
      'type' => 'required'
    ]);
    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }

    $public = false;
    if(isset($request->user_id)) {
      $user_id = $request->user_id;
      $public = true;
    }

    $activities = $request->activities;
    if(isset($activities)) {
      $activities = explode(',', $activities);
    }
    
    if($request->type == 'daily') {
      $start_date = Carbon::now()->format('Y-m-d');
      $end_date = Carbon::now()->format('Y-m-d');
    } else if($request->type == 'weekly') {
      $start_date = Carbon::now()->startOfWeek()->format('Y-m-d');
      $end_date = Carbon::now()->format('Y-m-d');
    }else if($request->type == 'monthly') {
      $start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
      $end_date = Carbon::now()->format('Y-m-d');
    } else {
      $start_date = Carbon::now()->format('Y-m-d');
      $end_date = Carbon::now()->format('Y-m-d');
    }

    $test = Tag::where('user_id', $user_id)
      ->whereBetween('date',[$start_date, $end_date])
      ->orderBy('date', 'desc')
      ->first();

    $actual_data = [];
    $budgeted_data = [];
    $bar_chat = [];
    $data = [];
    if (!$test) {
      return response()->json(['status' => false, 'message' => "Budget Tag Activity Details Not Available", 'data' => null], 200);
    } else {
      // 
      $totalmins = 24*60;

      $q = Tag::join('activitys','activitys.id','=','tags.tag_id')
        ->where('tags.user_id',$user_id)
        ->whereBetween('tags.date', [$start_date, $end_date])
        ->where('budgeted_start_time', '<>', '00:00')
        ->where('tags.budgeted_start_time', '<>', NULL)
        ->where('tags.budget_end_time', '<>', NULL)
        ->orderBy('tags.date', 'desc')
        ->join('categories','categories.id','=','activitys.parent_catgory')
        ->select('tags.*','activitys.activity','activitys.selectcolor', 'categories.title as category');
        

      // if(isset($request->activities)) {
      //   $q->whereIn('tags.tag_id', $activities);
      // }
      // if(isset($request->sub_category)) {
      //   $q->where('activitys.sub_category', $request->sub_category);
      // }

      if($public) {
        $q->where('activitys.selectprivacy', '1');
      }
      
      $budgeted_weak = $q->get();
      // dd($budgeted_weak);
      $numb = 0;
      $key = 0; 
      foreach($budgeted_weak as $key => $value) {

        $budgeted_time = number_format((float)((new Carbon($value->budgeted_start_time))->diffInMinutes(new Carbon($value->budget_end_time))),2,'.','');
        if(array_key_exists($value->category, $budgeted_data)) {
          $budgeted_data[$value->category]['time'] = $budgeted_data[$value->category]['time'] + number_format((float)$budgeted_time,2,'.','');
        } else {
          $budgeted_data[$value->category]['time'] = number_format((float)$budgeted_time,2,'.','');
          $budgeted_data[$value->category]['color'] = ($value->selectcolor)?$value->selectcolor:'';
        }
      }
      $totalmins = $totalmins * ($key + 1);

      $budgeted_records = [];
      foreach ($budgeted_data as $k => $val) {
        # code...
        $budgeted_records[] = [
          'title' =>$k,
          'time' => number_format(((((float)$val['time'])/$totalmins)*100),2,'.',''), 
          'color' => $val['color']
        ];
        $numb = $numb + number_format(((((float)$val['time'])/$totalmins)*100),2,'.',''); 
      }
      $budgeted_records[] = [
        'title' => 'Remaing',
        'time' => number_format((100-$numb),2,'.',''), 
        'color' => '0xffffffff'
      ];
      $data['budgeted_data'] = $budgeted_records;

      $q = Tag::join('activitys','activitys.id','=','tags.tag_id')
        ->where('tags.user_id',$user_id)
        ->whereBetween('tags.date', [$start_date, $end_date])
        ->orderBy('tags.date', 'desc')
        ->select('tags.*','activitys.activity','activitys.selectcolor')
        ->join('categories','categories.id','=','activitys.parent_catgory')
        ->select('tags.*','activitys.activity','activitys.selectcolor', 'categories.title as category');

      if($public) {
        $q->where('activitys.selectprivacy', '1');
      }
      $actual_weak = $q->get();
      $key = 0; 
      foreach($actual_weak as $key => $value) {

        $actual_time = number_format((float)((new Carbon($value->actual_start_time))->diffInMinutes(new Carbon($value->actual_end_time))),2,'.','');
        if(array_key_exists($value->category, $actual_data)) {
          $actual_data[$value->category]['time'] = $actual_data[$value->category]['time'] + number_format((float)$actual_time,2,'.','');
        } else {
          $actual_data[$value->category]['time'] = number_format((float)$actual_time,2,'.','');
          $actual_data[$value->category]['color'] = ($value->selectcolor)?$value->selectcolor:'';
        }

        $budgeted_time = (double)((new Carbon($value->budgeted_start_time))->diffInMinutes(new Carbon($value->budget_end_time)));
        if($value->budgeted_start_time == '' || $value->budgeted_start_time == '00:00') {
          $budgeted_time = 0;
        }
        if(array_key_exists($value->category, $bar_chat)) {
          $bar_chat[$value->category]['budgeted_time'] = $bar_chat[$value->category]['budgeted_time'] + number_format((float)$budgeted_time,2,'.','');
          $bar_chat[$value->category]['actual_time'] = $bar_chat[$value->category]['actual_time'] + number_format((float)$actual_time,2,'.','');
        } else {
          $bar_chat[$value->category]['budgeted_time'] = number_format((float)$budgeted_time,2,'.','');
          $bar_chat[$value->category]['actual_time'] = number_format((float)$actual_time,2,'.','');
          $bar_chat[$value->category]['color'] = ($value->selectcolor)?$value->selectcolor:'';;
        }
      }
      $totalmins = $totalmins * ($key + 1);
      $numb = 0;
      $actual_records = [];
      foreach ($actual_data as $k => $val) {
        # code...
        $actual_records[] = [
          'title' =>$k,
          'time' => number_format(((((float)$val['time'])/$totalmins)*100),2,'.',''),
          'color' => $val['color']
        ];
        $numb = $numb + number_format(((((float)$val['time'])/$totalmins)*100),2,'.','');
      }
      $actual_records[] = [
        'title' => 'Remaing',
        'time' => number_format((100-$numb),2,'.',''),
        'color' => '0xffffffff'
      ];
      $data['actual_data'] = $actual_records;

      $bar_chat_records = [];
      $numb = 0; 
      $numb2 = 0; 
      // 
      foreach ($bar_chat as $k => $val) {
        # code...
        $bar_chat_records[] = [
          'title' => $k,
          'budgeted_time' => number_format(((((float)$bar_chat[$k]['budgeted_time'])/$totalmins)*100),2,'.',''),
          'actual_time' => number_format(((((float)$bar_chat[$k]['actual_time'])/$totalmins)*100),2,'.',''),
          'color' => $bar_chat[$k]['color'],
        ];

        $numb = $numb + number_format(((((float)$bar_chat[$k]['budgeted_time'])/$totalmins)*100),2,'.',''); 
        $numb2 = $numb2 + number_format(((((float)$bar_chat[$k]['actual_time'])/$totalmins)*100),2,'.',''); 
      }

      $data['bar_chat'] = $bar_chat_records;
      
      return response()->json(['status' => true, 'message' => "Tag Activity Details", 'data' => $data,], 200);
    }
  }

  public function getSingleUserActivities(Request $request)
  {
    # code...
    $validator = Validator::make($request->all(), [
      'user_id' => 'required|numeric',
    ]);
    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }

    $users = Activity::where('username', $request->user_id)->get();
    if(count($users) == 0) {
      return response()->json(['status' => false, 'message' => "No Activity Found", 'activity' => []], 200);
    }
    $data = [];
    foreach ($users as $user) {
      $data[] = [
        'id' => $user->id,
        'userid' => $user->username,
        'activity' => $user->activity,
        'parent_catgory' => $user->parent_catgory,
        'sub_category' => $user->sub_category,
        'starttime' => $user->starttime,
        'endtime' => $user->endtime,
        'selectcolor' => $user->selectcolor,
        'selectprivacy' => $user->selectprivacy,
        'created_at' => $user->created_at,
        'updated_at' => $user->updated_at,
      ];
    }

    return response()->json(['status' => true, 'message' => "Activity Tags List", 'activity' => $data], 200);
  }

  public function daywisedata(Request $request)
  {
    $user_id = Auth::user()->id;
    $validator = Validator::make($request->all(), [
      'user_id' => 'numeric',
    ]);
    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }
    $todayDate = Carbon::now()->format('Y-m-d');
    if(isset($request->user_id)) {
      $user_id = $request->user_id;
    }
    $test =  Tag::where('user_id', $user_id)->where('date', $todayDate)->first();

    if ($test) {

      // if ($test->user_id==$user_id){
      // }
      // else{
      //   $today = Tag::join('activitys','activitys.id','=','tags.tag_id')->where('tags.user_id', $request->user_id)->where('tags.date', $todayDate) ->select('tags.*','activitys.activity')->get();
      // }

      $today = Tag::join('activitys','activitys.id','=','tags.tag_id')->where('tags.user_id', $user_id)->where('tags.date', $todayDate)->select('tags.*','activitys.activity')->get();
      

      foreach($today as $key=>$value){
        $today[$key]['total_time']=(new Carbon($value->budgeted_start_time))->diffInSeconds(new Carbon($value->budget_end_time));
        $today[$key]['actual_time']=(new Carbon($value->actual_start_time))->diffInSeconds(new Carbon($value->actual_end_time));
        // $gap=  $today[$key]['actual_time'] / $today[$key]['total_time'] * 100;
        $di_by = ($today[$key]['total_time'] > 0)?$today[$key]['total_time']:1;
        $di_to = ($today[$key]['actual_time'] > 0)?$today[$key]['actual_time']:1;

        $gap= $di_to / $di_by * 100;
        $today[$key]['budget']=str_replace(',', '', number_format($gap,2));
        if(!$today[$key]['budgeted_start_time']) { 
          $today[$key]['budgeted_start_time']= '';
        }
        if(!$today[$key]['budgeted_start_time']) { 
          $today[$key]['budget_end_time']= '';
        }
      }
      return response()->json(['status' => true, 'message' => "Toady  Budget Tag Activity Details", 'data' => $today,], 200);
    } else {

      return response()->json(['status' => false, 'message' => "Toady you have not do any Budget Tag Activity "], 200);
    }
  }


  public function weakwisedetals(Request $request)
  {
    $user_id = Auth::user()->id;
    $validator = Validator::make($request->all(), [
      'user_id' => 'numeric',
    ]);


    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }


    $test = Tag::where('user_id', $request->user_id)->orwhere('user_id',$user_id)
    ->whereBetween('date',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
    ->orderBy('date', 'desc')
    ->first();

    if ($test) {
      if($test->user_id==$user_id){
      $weak = Tag::join('activitys','activitys.id','=','tags.tag_id')->where('tags.user_id',$user_id)->whereBetween('tags.date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
      ->orderBy('tags.date', 'desc')
      ->select('tags.*','activitys.activity')
      ->get();
      }
      else{
        $weak = Tag::join('activitys','activitys.id','=','tags.tag_id')->where('tags.user_id',$request->user_id)->whereBetween('tags.date',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->orderBy('tags.date', 'desc')
        ->select('tags.*','activitys.activity')
        ->get();
      }
      foreach($weak as $key=>$value){
        $weak[$key]['total_time']=(new Carbon($value->budgeted_start_time))->diffInSeconds(new Carbon($value->budget_end_time));
        $weak[$key]['actual_time']=(new Carbon($value->actual_start_time))->diffInSeconds(new Carbon($value->actual_end_time));
        // $gap=  $weak[$key]['actual_time'] / $weak[$key]['total_time'] * 100;
        $di_by = ($weak[$key]['total_time'] > 0)?$weak[$key]['total_time']:1;
        $di_to = ($weak[$key]['actual_time'] > 0)?$weak[$key]['actual_time']:1;
        $gap= $di_to / $di_by * 100;
        $weak[$key]['budget']=str_replace(',', '', number_format($gap,2));
        if(!$weak[$key]['budgeted_start_time']) { 
          $weak[$key]['budgeted_start_time']= '';
        }
        if(!$weak[$key]['budgeted_start_time']) { 
          $weak[$key]['budget_end_time']= '';
        }
      }
     
      return response()->json(['status' => true, 'message' => "Weakely Budget Tag Activity Details", 'data' => $weak,], 200);
    } else {

      return response()->json(['status' => false, 'message' => "Weakely  Budget Tag Activity Details Not Available"], 200);
    }
  }

  public function monthlydetals(Request $request)
  {

     $user_id = Auth::user()->id;

    $validator = Validator::make($request->all(), [

      'user_id' => 'numeric',
    ]);

   
    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }



    $test = Tag::where('user_id', $request->user_id)->orwhere('user_id',$user_id)->whereMonth('date', Carbon::now()->month)->first();

    if ($test) {
      if($test->user_id==$user_id){
      $month = Tag::join('activitys','activitys.id','=','tags.tag_id')->where('tags.user_id',$user_id)->whereMonth('tags.date', Carbon::now()->month)
        ->orderBy('tags.date', 'asc')->select('tags.*','activitys.activity')->get(); 
      }
      else{
        $month = Tag::join('activitys','activitys.id','=','tags.tag_id')->where('user_id', $request->user_id)->whereMonth('tags.date', Carbon::now()->month)
        ->orderBy('tags.date', 'asc')->select('tags.*','activitys.activity')->get();
      }
      foreach($month as $key=>$value){
        $month[$key]['total_time']=(new Carbon($value->budgeted_start_time))->diffInSeconds(new Carbon($value->budget_end_time));
        $month[$key]['actual_time']=(new Carbon($value->actual_start_time))->diffInSeconds(new Carbon($value->actual_end_time));
        $di_by = ($month[$key]['total_time'] > 0)?$month[$key]['total_time']:1;
        $di_to = ($month[$key]['actual_time'] > 0)?$month[$key]['actual_time']:1;
        $gap= $di_to / $di_by * 100;
        $month[$key]['budget']=str_replace(',', '', number_format($gap,2));

        if(!$month[$key]['budgeted_start_time']) { 
          $month[$key]['budgeted_start_time']= '';
        }
        if(!$month[$key]['budgeted_start_time']) { 
          $month[$key]['budget_end_time']= '';
        }
      }
     
      return response()->json(['status' => true, 'message' => "Monthly Budget Tag Activity Details", 'data' => $month,], 200);
    } else {

      return response()->json(['status' => false, 'message' => "Monthly Budget Tag  Activity Details Not Available"], 200);
    }
  }


  public function updateTagStatus(Request $request)
  {
    # code...
    if (Auth::guard('api')->check()) {
      $user = Auth::guard('api')->user();
    }
    if (!$user) {
      return response()->json(['status' => false, 'message' => 'login token error!', 'data' => []]);
    }
    $validator = Validator::make($request->all(), [
      'tag_id' => 'required|numeric',
    ]);

    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }

    $parameters = $request->all();
    extract($parameters);
    
    Tag::where('id', $tag_id)->update([
    'status' => true,
    ]);

    return response()->json(['status' => true, 'message' => 'Status updateded successfully!']);
  }
}
