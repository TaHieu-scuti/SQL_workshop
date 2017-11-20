<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\RepoAdwAccountReportCost;
use Auth;

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

    public static function getAllAccounts()
    {
        $arrayAccounts = [];

        $accounts = self::select('accountName', 'accountid')
            ->where('account_id', '=', Auth::user()->account_id);

        $adwAccount = RepoAdwAccountReportCost::select('account AS accountNAme', 'accountid')
            ->where('account_id', '=', Auth::user()->account_id);

        $accounts->union($adwAccount);

        $datas = $accounts->get();

        $arrayAccounts['all'] = 'All Account';
        // hieu here
//        if ($datas) {
//            foreach ($datas as $key => $account) {
//                var_dump($account->accountName);
//                $arrayAccounts[$account->accountid] = $account->accountName;
//            }
//        }
        return $datas->toArray();
    }
}
