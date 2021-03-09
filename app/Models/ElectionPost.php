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
}
