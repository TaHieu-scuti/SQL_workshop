<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use App\Model\RepoYssAccount;
use App\Model\RepoYssCampaignReportCost;
use App\Http\Controllers\AbstractReportController;

class User extends Authenticatable
{
    use Notifiable;
    const ENGINE = 'engine';
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
                $array['title'] = $title;
                $array['contents'] = $model->getAllAccounts(
                    session('accountID')
                );
                if (session('accountID') === null) {
                    $array['flag'] = 'all';
                } else {
                    $array['flag'] = session('accountID');
                }
                break;
            case 'Campaign':
                $model = new RepoYssCampaignReportCost;
                $array['title'] = $title;
                $array['contents'] = $model->getAllCampaign(
                    session(AbstractReportController::SESSION_KEY_ACCOUNT_ID),
                    session(AbstractReportController::SESSION_KEY_CAMPAIGNID),
                    session(AbstractReportController::SESSION_KEY_AD_GROUP_ID),
                    session(AbstractReportController::SESSION_KEY_AD_REPORT_ID),
                    session(AbstractReportController::SESSION_KEY_KEYWORD_ID)
                );
                if (session('campainID') === null) {
                    $array['flag'] = 'all';
                } else {
                    $array['flag'] = session('campainID');
                }
                if (session(AbstractReportController::SESSION_KEY_ENGINE) !== null) {
                    $array[self::ENGINE] =  session(AbstractReportController::SESSION_KEY_ENGINE);
                }
                break;
            case 'AdGroup':
                $model = new \App\Model\RepoYssAdgroupReportCost;
                $array['title'] = $title;
                $array['contents'] = $model->getAllAdgroup(
                    session(AbstractReportController::SESSION_KEY_ACCOUNT_ID),
                    session(AbstractReportController::SESSION_KEY_CAMPAIGNID),
                    session(AbstractReportController::SESSION_KEY_AD_GROUP_ID),
                    session(AbstractReportController::SESSION_KEY_AD_REPORT_ID),
                    session(AbstractReportController::SESSION_KEY_KEYWORD_ID)
                );
                if (session('adgroupId') === null) {
                    $array['flag'] = 'all';
                } else {
                    $array['flag'] = session('adgroupId');
                }
                if (session(AbstractReportController::SESSION_KEY_ENGINE) !== null) {
                    $array[self::ENGINE] =  session(AbstractReportController::SESSION_KEY_ENGINE);
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
