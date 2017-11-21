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

    public static function getArrayAttribute($title)
    {

        $array = [];
        switch ($title) {
            case 'Account':
                $model = new RepoYssAccount;
                $array[] = $title;
                $array[] = $model->getAllAccounts();
                if (session('accountID') === null) {
                    $array['flag'] = 'all';
                } else {
                    $array['flag'] = session('accountID');
                }
                break;
            case 'Campaign':
                $model = new RepoYssCampaignReportCost;
                $array[] = $title;
                $array[] = $model->getAllCampaign();
                if (session('campainID') === null) {
                    $array['flag'] = 'all';
                } else {
                    $array['flag'] = session('campainID');
                }
                if ( session('engine') !== null ) {

                   $array['engine'] =  session('engine');
                }
                break;
            case 'AdGroup':
                $model = new \App\Model\RepoYssAdgroupReportCost;
                $array[] = $title;
                $array[] = $model->getAllAdgroup();
                if (session('adgroupId') === null) {
                    $array['flag'] = 'all';
                } else {
                    $array['flag'] = session('adgroupId');
                }
                if ( session('engine') !== null ) {
                    $array['engine'] =  session('engine');
                }
                break;
            case 'AdReport':
                $model = new \App\Model\RepoYssAdReportCost;
                $array[] = $title;
                $array[] = $model->getAllAdReport(
                    session('accountID'),
                    session('campainID'),
                    session('adgroupId'),
                    session('adReportId')
                );
                if (session('adReportId') === null) {
                    $array['flag'] = 'all';
                } else {
                    $array['flag'] = session('adReportId');
                }
                if ( session('engine') !== null ) {
                    $array['engine'] =  session('engine');
                }
                break;
            case 'KeyWord':
                $model = new \App\Model\RepoYssKeywordReportCost;
                $array[] = $title;
                $array[] = $model->getAllKeyword(
                    session('accountID'),
                    session('campainID'),
                    session('adgroupId'),
                    session('KeywordID')
                );
                if (session('KeywordID') === null) {
                    $array['flag'] = 'all';
                } else {
                    $array['flag'] = session('adReportId');
                }
                if ( session('engine') !== null ) {
                    $array['engine'] =  session('engine');
                }
                break;
            
            default:
                // code...
                break;
        }
        return $array;
    }
    public static function getIdAdgainer()
    {
        return Auth::user()->account_id;
    }
}
