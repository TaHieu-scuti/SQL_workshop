<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PhoneTimeUse extends Model
{
    public $timestamps = false;
    protected $table = 'phone_time_use';

    public function repoPhoneTimeUse()
    {
        return $this->hasOne('App\Model\RepoPhoneTimeUse');
    }
}
