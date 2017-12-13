<?php

namespace App;

use App\Model\Account;
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
            case 'Client':
                $model = new Account();
                $array['title'] = $title;
                $array['contents'] = $model->getAllClient();
                if (session(AbstractReportController::SESSION_KEY_ACCOUNT_ID) === null) {
                    $array['flag'] = 'all';
                } else {
                    $array['flag'] = session(AbstractReportController::SESSION_KEY_ACCOUNT_ID);
                }
                break;
            case 'Account':
                $model = new RepoYssAccount;
                $array['title'] = $title;
                $array['contents'] = $model->getAllAccounts();
                if (session(AbstractReportController::SESSION_KEY_ACCOUNT_ID) === null) {
                    $array['flag'] = 'all';
                } else {
                    $array['flag'] = session(AbstractReportController::SESSION_KEY_ACCOUNT_ID);
                }
                break;
            case 'Campaign':
                $model = new RepoYssCampaignReportCost;
                $array['title'] = $title;
                $array['contents'] = $model->getAllCampaign(
                    session(AbstractReportController::SESSION_KEY_ACCOUNT_ID)
                );
                if (session(AbstractReportController::SESSION_KEY_CAMPAIGNID) === null) {
                    $array['flag'] = 'all';
                } else {
                    $array['flag'] = session(AbstractReportController::SESSION_KEY_CAMPAIGNID);
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
                    session(AbstractReportController::SESSION_KEY_CAMPAIGNID)
                );
                if (session(AbstractReportController::SESSION_KEY_AD_GROUP_ID) === null) {
                    $array['flag'] = 'all';
                } else {
                    $array['flag'] = session(AbstractReportController::SESSION_KEY_AD_GROUP_ID);
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
