<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectionCandidate extends Model
{
    protected $fillable = ['user_id', 'election_id', 'election_post_id', 'symbol', 'status'];

    public function post()
    {
        return $this->belongsTo(ElectionPost::class, 'election_post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function election()
    {
        return $this->belongsTo(Election::class, 'election_id');
    }
}
