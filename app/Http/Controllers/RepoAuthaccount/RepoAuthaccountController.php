<?php

namespace App\Http\Controllers\RepoAuthaccount;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\RepoAuthaccount;
use App\Http\Requests\AuthAccountRequest;

class RepoAuthaccountController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Authaccounts = RepoAuthaccount::orderby('id', 'desc')->paginate(5);
        return view('authAccount.index', ['Authaccounts' => $Authaccounts]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('authAccount.CreateAuthAccount');
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuthAccountRequest $request)
    {
        $data = $request->all();
        RepoAuthaccount::create($data);
        return redirect()->route('auth-account');
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Authaccount = RepoAuthaccount::find($id);
        return view('authAccount.EditAuthAccount', ['Authaccount' => $Authaccount]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(AuthAccountRequest $request)
    {
        $Authaccount = RepoAuthaccount::where('id', $id);

        $Authaccount->update([
            'license' => $request->license,
            'apiAccountId' => $request->apiAccountId,
            'apiAccountPassword' => $request->apiAccountPassword,
            'accountId' => $request->accountId,
            'onBehalfOfAccountId' => $request->onBehalfOfAccountId,
            'onBehalfOfPassword' => $request->onBehalfOfPassword,
            'developerToken' => $request->developerToken,
            'userAgent' => $request->userAgent,
            'clientCustomerId' => $request->clientCustomerId,
        ]);
        return redirect()->route('auth-account');
    }
}
