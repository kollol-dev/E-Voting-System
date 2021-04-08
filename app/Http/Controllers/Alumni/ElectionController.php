<?php

namespace App\Http\Controllers\Alumni;

// models 
use App\Models\User;
use App\Models\Election;
use App\Models\ElectionPost;
use App\Models\ElectionCandidate;
use App\Models\Vote;

// helpers
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DateTime;
use Illuminate\Support\Facades\Validator;


class ElectionController extends Controller
{
    public function getCandidateByELectionId($id)
    {
        return ElectionCandidate::where('election_post_id', $id)
            ->where('status', 'approved')
            ->with('user')
            ->with('post')
            ->with('election')
            ->get();
    }
    public function getPostsByELectionId($id)
    {
        return ElectionPost::where('election_id', $id)
            ->with('candidates')
            ->get();
    }




    public function checkVoteCastByElectionId($id)
    {
        $check = Vote::where('user_id', Auth::id())
            ->where('election_id', $id)
            ->count();

        $checkApplied = ELectionCandidate::where('user_id', Auth::id())
            ->where('election_post_id', $id)
            ->count();

        $isApplied = $checkApplied == 0 ? false :  true;

        if ($check != 0) {
            return response()->json([
                "is_vote_casted" => true,
                "isApplied" => $isApplied
            ]);
        }
        return response()->json([
            "is_vote_casted" => false,
            "isApplied" => $isApplied
        ]);
    }

    public function castVote(Request $request, $id)
    {
        // $validation = Validator::make($request->all(), [
        //     'post_id' => ['required'],
        // // ]);
        // if ($validation->fails()) {
        //     return response()->json($validation->messages(), 401);
        // } else {



        $check = Vote::where('user_id', Auth::id())
            ->where('election_id', $id)
            ->count();

        if ($check != 0) {
            return response()->json([
                "message" => 'Your vote has been casted already!'
            ]);
        }


        $data = $request->all();
        $selectedIndex = [];


        foreach ($data['posts'] as $iii) {
            if ($iii['selectedIndex'] > -1) {
                array_push($selectedIndex, $iii['id']);
            }
        }

        foreach ($selectedIndex as $i) {
            Vote::create([
                "user_id" => Auth::id(),
                "election_id" => $id,
                "post_id" => $i
            ]);
        }

        return 'done';
        // }
    }



    public function candidateApply(Request $request, $id)
    {
        $electionPost = ElectionPost::where('id', $id)->first();
        return ElectionCandidate::create([
            'user_id' => Auth::id(),
            'election_id' => $electionPost->election_id,
            'election_post_id' => $id
        ]);
    }
}
