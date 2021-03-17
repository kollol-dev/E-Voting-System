<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ElectionPost extends Model
{
    protected $fillable = ['name', 'election_id'];


    public function election()
    {
        return $this->belongsTo(Election::class, 'election_id');
    }

    public function candidates()
    {
        return $this->hasMany(ElectionCandidate::class, 'election_post_id');
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'post_id');
    }
}
