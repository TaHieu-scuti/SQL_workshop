<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\AuthAccountRequest;
use Auth;
use App\Model\RepoAuthAccount;

class UpdateAuthAccountRequest extends AuthAccountRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $authAcount = RepoAuthAccount::find($this->route()->parameters['id']);
        $account_id = !is_null(Auth::user()) ? Auth::user()->account_id : Auth::guard('redisGuard')->user()->account_id;
        if ($account_id === $authAcount->account_id) {
            return true;
        }
        return false;
    }
}
