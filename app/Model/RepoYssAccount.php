<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\RepoAdwAccountReportCost;
use App\Model\RepoYdnAccount;
use App\Http\Controllers\AbstractReportController;
use Auth;
use DB;

class RepoYssAccount extends Model
{
    protected $table = 'repo_yss_accounts';
    protected $fillable = [
        'accountid',    // Account ID
        'account_id',    // Account ID of ADgainer system
        'accountName',    // Account name
        'accountType',    // Type of account
        'accountStatus',    // Contract status of the account
        'deliveryStatus',    // Delivery status of the ad
    ];

    public function getAllAccounts()
    {
        $accounts = self::select(DB::raw('"yss" as engine'), 'accountName', 'accountid')
            ->where('account_id', '=', session(AbstractReportController::SESSION_KEY_ADGAINER_ID));

        $adwAccounts = RepoAdwAccountReportCost::select(
            DB::raw('"adw" as engine'),
            'account AS accountNAme',
            'customerID as accountId'
        )
            ->where('account_id', '=', session(AbstractReportController::SESSION_KEY_ADGAINER_ID));

        $ydnAccounts = RepoYdnAccount::select(DB::raw('"ydn" as engine'), 'accountName', 'accountId as accountid')
            ->where('account_id', '=', session(AbstractReportController::SESSION_KEY_ADGAINER_ID));
        $accounts->union($adwAccounts)->union($ydnAccounts);
        $arr = ['all' => 'All Account'];
        $datas = $accounts->get();
        $datas = $datas->toArray();

        return $arr + $datas;
    }
}
