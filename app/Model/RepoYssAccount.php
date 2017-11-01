<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
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

        $accounts = self::select('accountName', 'accountid')->where('account_id', '=', Auth::user()->account_id)->get();

        $arrayAccounts['all'] = 'All Account';
        
        if ($accounts) {
            foreach ($accounts as $key => $account) {
                $arrayAccounts[$account->accountid] = $account->accountName;
            }
        }
        
        return $arrayAccounts;
    }
}
