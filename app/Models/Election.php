<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    protected $fillable = ['name', 'date_and_time', 'candidate_application_expiration_date_time'];

    public function totalVotes()
    {
        return $this->hasMany(Vote::class, 'election_id');
    }

    public function posts()
    {
        return $this->hasMany(ElectionPost::class, 'election_id');
    }

    public function isVoted()
    {
        return $this->hasMany(Vote::class, 'election_id');
    }
}
