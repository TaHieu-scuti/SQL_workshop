<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepoYdnAccount extends Model
{
    protected $table = 'repo_ydn_accounts';
    protected $fillable = [
        'accountid',    // Account ID
        'account_id',    // Account ID of ADgainer system
        'accountName',    // Account name
        'accountType',    // Payment of the fee
        'accountStatus',    // Contract status of the account
        'deliveryStatus',    // Delivery status of the ad
    ];
}
