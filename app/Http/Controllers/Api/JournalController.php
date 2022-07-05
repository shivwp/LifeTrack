<?php




namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Journal_Management;
use Carbon\Carbon;
use Validator;
use Auth;

class JournalController extends Controller
{
    public function journal(Request $request)
    {
        $user_id = Auth::user()->id;

        // $validator = Validator::make($request->all(), [
        //     'date' => 'date',
        //     // 'to' => 'date|after:from',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        // }

        $q = Journal_Management::where('user_id', $user_id);

        if (isset($request->date)) {
            $from = $request->date;
            $q->where('date', $from);
        }

        // if (isset($request->from) && !isset($request->to)) {
        //     $from = $request->from;
        //     $currentTime = Carbon::now();
        //     $q->whereBetween('date', [$from . ' 00:00:00', $currentTime . ' 23:59:59']);
        // }

        $getjnrls = $q->orderBy('created_at', 'DESC')->get();

        if ($getjnrls->count() > 0) {
            return response()->json(['status' => true, 'message' => "Get Journals Details", 'data' => $getjnrls], 200);
        } else {
            return response()->json(['status' => false, 'message' => "Journal details not found", 'data' => []], 200);
        }
    }


    public function addjournal(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date',

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        $user_id = Auth::user()->id;



        if (!empty($request->id)) {

            $user = Journal_Management::updateOrCreate(['id' => $request->id], [

                'user_id'      => Auth::user()->id,
                'name'         => $request->name,
                'status'       => $request->status,
                'description'  => $request->description,
                'date'         => $request->date,
            ]);
        } else {
            $user = Journal_Management::updateOrCreate(['id' => $request->id], [

                'user_id'      => Auth::user()->id,
                'name'         => $request->name,
                'status'       => $request->status,
                'description'  => $request->description,
                'date'         => $request->date,

            ]);
        }
        $data = [

            'user_id' => $user_id,
            'description'  => $request->description,
            'date'         => $request->date,
            'name'  => $request->name,
        ];

        if ($request->id) {

            $message = "Journal in Updated";
        } else {
            $message = "Add New Journal";
        }

        return response()->json(['status' => true, 'message' => $message, 'data' => $data], 200);
    }

    public function showsinglejnrl()
    {

        $user_id = Auth::user()->id;
        $users = Journal_Management::where('user_id', $user_id)->get();




        // foreach ($users as $key => $user) {

        //     $data=[

        //         "request_id"=>$user->id,
        //         "user_id"=>$user->user_id,
        //         "name"=>$user->name,
        //         "description"=> $user->description

        //     ];
        // }
        if (!$users) {
            return response()->json(['status' => false, 'message' => "Not Available Journal List  "], 200);
        } else {
            return response()->json(['status' => true, 'message' => "Show Journal List", 'data' => $users], 200);
        }
    }
    public function singlejnrl(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'id' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }
        $user_id = Auth::user()->id;
        $users = Journal_Management::where('id', $request->id)->where('user_id', $user_id)->first();


        if ($users) {

            $data = [

                "description" => $users->description,

            ];

            return response()->json(['status' => true, 'message' => "show Single jnrl list", 'data' => $users], 200);
        } else {

            return response()->json(['status' => false, 'message' => "You Have not Jornral post"], 200);
        }
    }
}
