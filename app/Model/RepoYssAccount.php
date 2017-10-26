<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

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
    	$arrayAccouts = [];
    	$accounts = self::select('accountName', 'id')->get();
    	array_push($arrayAccouts, 'All Account');
    	if ($accounts) {
    		foreach ($accounts as $key => $account) {
	    		$arrayAccouts[$account->id] = $account->accountName;
	    	}
    	}
    	return $arrayAccouts;
    }
}
