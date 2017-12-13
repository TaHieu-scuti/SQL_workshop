<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RepoAuthaccount extends Model
{
    protected $table = 'repo_authaccounts';
    protected $fillable = [
        'account_id',
        'license',
        'apiAccountId',
        'apiAccountPassword',
        'accountId',
        'onBehalfOfAccountId',
        'onBehalfOfPassword',
        'developerToken',
        'userAgent',
        'clientCustomerId',
    ];

    /**
     * @var boolean
     **/
    public $timestamps = false;
}
