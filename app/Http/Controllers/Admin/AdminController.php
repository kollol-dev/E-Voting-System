<?php

namespace App\Http\Controllers\Admin;

// models 
use App\Models\User;

// helpers
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    // get all users 
    public function getAllUsers(Request $request)
    {
        return User::where('role', '!=', 'admin')->get();
    }

    // paginate all users 
    public function paginateAllUsers(Request $request)
    {
        $page = isset($request->page) ? $request->page : 1;
        return User::where('role', '!=', 'admin')->paginate(20, ["*"], 'page', $page);
    }

    // edit user
    public function editUser(Request $request, $id)
    {
        $check = Auth::user();
        if (!$check || $check['role'] != 'admin') {
            return response()->json([
                'message' => 'You are not authorized update!'
            ], 401);
        }

        $check = User::where('id', $id)->first();
        if (!isset($check)) {
            return response()->json([
                'message' => 'Invalid Request!'
            ], 403);
        }
        return User::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => isset($request->password) ? Hash::make($request->password) : $check->password,
            'status' => $request->status,
        ]);
    }

    // delete user
    public function deleteUser($id)
    {
        $check = Auth::user();
        if (!$check || $check['role'] != 'admin') {
            return response()->json([
                'message' => 'You are not authorized to delete!'
            ], 401);
        }

        $check = User::where('id', $id)->count();
        if ($check == 0) {
            return response()->json([
                'message' => 'Invalid Request!'
            ], 403);
        }

        return User::where('id', $id)->delete();
    }
}
