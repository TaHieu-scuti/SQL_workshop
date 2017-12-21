<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RepoPhoneTimeUse extends Model
{
    public $timestamps = false;
    protected $table = 'repo_phone_time_use';

    public function phoneTimeUse()
    {
        return $this->belongsTo('App\Model\PhoneTimeUse');
    }
}
