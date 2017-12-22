<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RepoAuthAccount extends Model
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
        'media',
    ];

    /**
     * @var boolean
     **/
    public $timestamps = false;
}
