<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute($password)
    {

        $this->attributes['password'] = md5($password);
    }

    public static function getArrayAttribute($title) {
        $array = [];
        if ($title === "Account") {
            $array[] = $title;
            $array[] = Auth::user()->username;
        } elseif ($title === "Campaign") {
            $array[] = $title;
            $array[] = "All Campaign";
        } elseif ($title === "AdGroup") {
            $array[] = $title;
            $array[] = "All AdGroup";
        } elseif ($title === "AdReport") {
            $array[] = $title;
            $array[] = "All AdReport";
        }
        return $array;
    }
}
