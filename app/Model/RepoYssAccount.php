<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\RepoAdwAccountReportCost;
use App\Model\RepoYdnAccount;
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
            ->where('account_id', '=', Auth::user()->account_id);

        $adwAccounts = RepoAdwAccountReportCost::select(
            DB::raw('"adw" as engine'),
            'account AS accountNAme',
            'accountid'
        )
            ->where('account_id', '=', Auth::user()->account_id);

        $ydnAccounts = RepoYdnAccount::select(DB::raw('"ydn" as engine'), 'accountName', 'accountId as accountid')
            ->where('account_id', '=', Auth::user()->account_id);

        $accounts->union($adwAccounts)->union($ydnAccounts);
        $datas = $accounts->get();
        $datas['all'] = 'All Account';

        return $datas->toArray();
    }
}
