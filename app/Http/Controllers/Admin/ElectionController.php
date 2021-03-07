<?php

namespace App\Http\Controllers\Admin;

// models 
use App\Models\User;
use App\Models\Election;

// helpers
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DateTime;
use Illuminate\Support\Facades\Validator;

class ElectionController extends Controller
{
    // get all elections 
    public function getAllElection(Request $request)
    {
        return Election::all();
    }

    // paginate all elections 
    public function paginateElection(Request $request)
    {
        $page = isset($request->page) ? $request->page : 1;
        return Election::paginate(20, ["*"], 'page', $page);
    }


    public function addNewElection(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'date_and_time' => ['required'],
        ]);
        if ($validation->fails()) {
            return response()->json($validation->messages(), 401);
        } else {
            // return date($request->date_and_time);
            $election = Election::create([
                'name' => $request->name,
                'date_and_time' => (new DateTime($request->date_and_time))->format("Y-m-d H:i:s")
            ]);
            return $election;
        }
    }

    // edit user
    public function updateElection(Request $request, $id)
    {
        $check = Auth::user();
        if (!$check || $check['role'] != 'admin') {
            return response()->json([
                'message' => 'You are not authorized update!'
            ], 401);
        }

        $check = Election::where('id', $id)->first();
        if (!isset($check)) {
            return response()->json([
                'message' => 'Invalid Request!'
            ], 403);
        }
        return Election::where('id', $id)->update([
            'name' => $request->name,
            'date_and_time' => (new DateTime($request->date_and_time))->format("Y-m-d H:i:s"),
        ]);
    }

    // delete user
    public function deleteElection($id)
    {
        $check = Auth::user();
        if (!$check || $check['role'] != 'admin') {
            return response()->json([
                'message' => 'You are not authorized to delete!'
            ], 401);
        }

        $check = Election::where('id', $id)->count();
        if ($check == 0) {
            return response()->json([
                'message' => 'Invalid Request!'
            ], 403);
        }

        return Election::where('id', $id)->delete();
    }
}
