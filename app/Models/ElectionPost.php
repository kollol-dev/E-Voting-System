<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectionPost extends Model
{
    protected $fillable = ['name', 'election_id'];


    public function election()
    {
        return $this->belongsTo(Election::class, 'election_id');
    }

    public function candidates()
    {
        return $this->hasOne(ElectionCandidate::class, 'election_post_id');
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }
}
