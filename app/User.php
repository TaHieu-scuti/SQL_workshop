<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use App\Model\RepoYssAccount;
use App\Model\RepoYssCampaignReportCost;

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
        switch ($title) {
            case 'Account':
                $array[] = $title;
                $array[] = RepoYssAccount::getAllAccounts();
                if (session('accountID') === null) {
                    $array['flag'] = 'all';
                } else {
                    $array['flag'] = session('accountID');
                }
                break;
            case 'Campaign':
                $array[] = $title;
                $array[] = RepoYssCampaignReportCost::getAllCampaign();
                if (session('campainID') === null) {
                    $array['flag'] = 'all';
                } else {
                    $array['flag'] = session('campainID');
                }
                break;
            case 'AdGroup':
                $array[] = $title;
                $array[] = \App\Model\RepoYssAdgroupReportCost::getAllAdgroup();
                if (session('adgroupId') === null) {
                    $array['flag'] = 'all';
                } else {
                    $array['flag'] = session('adgroupId');
                }
                break;
            case 'AdReport':
                 $array[] = $title;
                $array[] = \App\Model\RepoYssAdReportCost::getAllAdReport();
                if (session('adReportId') === null) {
                    $array['flag'] = 'all';
                } else {
                    $array['flag'] = session('adReportId');
                }
                break;
            
            default:
                # code...
                break;
        }
        return $array;
    }
    public static function getIdAdgainer()
    {
        return Auth::user()->account_id;
    }
}
