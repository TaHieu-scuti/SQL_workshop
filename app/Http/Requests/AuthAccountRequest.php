<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class AuthAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (!Auth::check()) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'license' => 'max:19',
            'apiAccountId' => 'max:19',
            'apiAccountPassword' => 'max:255',
            'accountId' => 'max:20',
            'onBehalfOfAccountId' => 'max:20',
            'onBehalfOfPassword' => 'max:255s',
            'developerToken' => 'max:22',
            'clientCustomerId' => 'max:12',
        ];
    }
}
