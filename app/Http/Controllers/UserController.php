<?php

namespace App\Http\Controllers;

// models 
use App\Models\User;
use App\Models\Election;

// helpers
use Illuminate\Http\Request;
use Auth;
use Hash;
use Session;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function login(Request $request)
    {
        if (!isset($request->email) || !isset($request->password)) {
            return response()->json([
                'message' => 'Invalid Request',
            ], 403);
        }

        if ((User::where('email', $request->email)->count()) == 0) {
            return response()->json([
                'message' => "Email doesn't exist!",
            ], 422);
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return Auth::user();
        } else {
            return response()->json([
                'message' => "Invalid login credentials.",
            ], 422);
        }
    }
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8']
        ]);

        if ($validation->fails()) {
            return response()->json($validation->messages(), 401);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 'pending'
            ]);

            Auth::attempt(['email' => $request->email, 'password' => $request->password]);

            return $user;
        }
    }
    public function logout()
    {

        Auth::logout();
        Session::flush();
        return redirect("/login");
    }

    public function getOverviewByElection($id)
    {
        return Election::where('id', $id)
            ->with(['posts' => function ($builder) {
                $builder->withCount('votes');
                }, 'posts.winner', 'posts.candidates'])
            ->with('isVoted')
            ->withCount('totalVotes')
            ->first();
    }

    public function getElections()
    {
        return Election::all();
    }
}
