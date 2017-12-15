<?php

namespace App\Http\Controllers\RepoAuthAccount;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\RepoAuthAccount;
use App\Http\Requests\AuthAccountRequest;
use App\Http\Requests\UpdateAuthAccountRequest;
use Auth;

class RepoAuthAccountController extends Controller
{
    private $model;

    public function __construct(RepoAuthAccount $model)
    {
        $this->model = $model;
    }
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $authAccounts = RepoAuthAccount::where('account_id', Auth::user()->account_id)->paginate(20);
        return view('authAccount.index', ['authAccounts' => $authAccounts]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('authAccount.CreateAuthAccount');
    }

    /**
     * @param  \Illuminate\Http\AuthAccountRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuthAccountRequest $request)
    {
        $data = $request->all();
        RepoAuthAccount::create($data);
        return redirect()->route('auth-account');
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $authAccount = RepoAuthAccount::find($id);
        return view('authAccount.EditAuthAccount', ['authAccount' => $authAccount]);
    }

    /**
     * @param  \Illuminate\Http\UpdateAuthAccountRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAuthAccountRequest $request, $id)
    {
        $authAccount = RepoAuthAccount::where('id', $id);
        $authAccount->update([
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
