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
        if ($request->userAgent === 'google') {
            $this->validate($request, [
                'account_id' => 'required|max:50',
                'developerToken' => 'required|max:22',
                'clientCustomerId' => 'required|max:12',
                'onBehalfOfAccountId' => 'required|max:20',
                'onBehalfOfPassword' => 'required|max:255',
            ]);
        } else {
            $this->validate($request, [
                'account_id' => 'required|max:50',
                'license' => 'required|max:19',
                'accountId' => 'required|max:20',
                'apiAccountId' => 'required|max:19',
                'apiAccountPassword' => 'required|max:255',
            ]);
        }
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
