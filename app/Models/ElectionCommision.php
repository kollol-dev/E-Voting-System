<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectionCommision extends Model
{
    protected $fillable = ['user_id', 'position'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
