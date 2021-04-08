<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectionCommision extends Model
{
    protected $fillable = ['user_id', 'position'];

    public function user()
    {
        return $this->hasOne(ElectionCommisionUser::class, 'election_commision_id');
    }
}
