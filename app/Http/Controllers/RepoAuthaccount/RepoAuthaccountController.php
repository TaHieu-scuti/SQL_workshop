<?php

namespace App\Http\Controllers\RepoAuthAccount;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Model\RepoAuthAccount;
use App\Model\Account;
use App\Http\Requests\AuthAccountRequest;
use App\Http\Requests\UpdateAuthAccountRequest;
use Auth;

class RepoAuthAccountController extends Controller
{
    private $model;

    const ADW_MEDIA = 0;
    const YDN_MEDIA = 1;
    const YSS_MEDIA = 2;

    public function __construct(RepoAuthAccount $model)
    {
        $this->model = $model;
        $this->middleware(
            function (Request $request, $next) {
                $accountModel = new Account();
                $account_id = Auth::user()->account_id;
                if ($accountModel->isAdmin($account_id)) {
                    return redirect('agency-report');
                }
                session()->put([
                    'clientId' => $account_id
                ]);
                return $next($request);
            }
        );
    }
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accountModel = new Account();
        $account_id = Auth::user()->account_id;
        $authAccountModel = new RepoAuthAccount();
        if (!$accountModel->isAgency($account_id)) {
            return redirect()->route('config-account', ['id' => $account_id]);
        }
        $authAccounts = $authAccountModel->getAuthAccountByAgentId($account_id);
        return view('authAccount.index', ['authAccounts' => $authAccounts]);
    }

    /**
     * @param  \Illuminate\Http\AuthAccountRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$this->checkPermissionUpdateAccountApi($request->account_id)) {
            return 'No permission';
        }

        if ((int)$request->media === self::ADW_MEDIA) {
            $this->validate($request, [
                'account_id' => 'required|max:50',
                'userAgent' => 'required',
                'developerToken' => 'required|max:22',
                'clientCustomerId' => 'required|max:12',
                'onBehalfOfAccountId' => 'required|max:20',
                'onBehalfOfPassword' => 'required|max:255',
            ]);
        } else {
            $this->validate($request, [
                'account_id' => 'required|max:50',
                'userAgent' => 'required',
                'license' => 'required|max:19',
                'accountId' => 'required|max:20',
                'apiAccountId' => 'required|max:19',
                'apiAccountPassword' => 'required|max:255',
            ]);
        }
        $data = $request->all();
        RepoAuthAccount::create($data);
        return response()->json(['create' => 'success']);
    }

    /**
     * @param  \Illuminate\Http\UpdateAuthAccountRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $authAccount = RepoAuthAccount::find($id);
        if ((int)$authAccount->media === self::ADW_MEDIA) {
            $this->validate($request, [
                'userAgent' => 'required',
                'developerToken' => 'required|max:22',
                'clientCustomerId' => 'required|max:12',
                'onBehalfOfAccountId' => 'required|max:20',
                'onBehalfOfPassword' => 'required|max:255',
            ]);
            $authAccount->update([
                'userAgent' => $request->userAgent,
                'developerToken' => $request->developerToken,
                'clientCustomerId' => $request->clientCustomerId,
                'onBehalfOfAccountId' => $request->onBehalfOfAccountId,
                'onBehalfOfPassword' => $request->onBehalfOfPassword,
            ]);
        } else {
            $this->validate($request, [
                'userAgent' => 'required',
                'license' => 'required|max:19',
                'accountId' => 'required|max:20',
                'apiAccountId' => 'required|max:19',
                'apiAccountPassword' => 'required|max:255',
            ]);
            $authAccount->update([
                'userAgent' => $request->userAgent,
                'license' => $request->license,
                'accountId' => $request->accountId,
                'apiAccountId' => $request->apiAccountId,
                'apiAccountPassword' => $request->apiAccountPassword,
            ]);
        }
        return response()->json(['update' => 'success']);
    }

    public function config($account_id)
    {
        $accountModel = new Account();
        $currentAccountId = Auth::user()->account_id;
        $isAgency = false;
        $isEmptyApi = false;

        if (!$this->checkPermissionUpdateAccountApi($account_id)) {
            return 'No permission';
        }

        if ($accountModel->isAgency($currentAccountId)) {
            $isAgency = true;
        }

        $adw_record = RepoAuthAccount::where('account_id', $account_id)->where('media', self::ADW_MEDIA)->first();
        $ydn_record = RepoAuthAccount::where('account_id', $account_id)->where('media', self::YDN_MEDIA)->first();
        $yss_record = RepoAuthAccount::where('account_id', $account_id)->where('media', self::YSS_MEDIA)->first();

        if ($adw_record === null && $ydn_record === null && $yss_record === null) {
            $isEmptyApi = true;
        }

        return view('authAccount.config_account', [
            'account_id' => $account_id,
            'isAgency' => $isAgency,
            'isEmptyApi' => $isEmptyApi,
            'adw_record' => $adw_record,
            'ydn_record' => $ydn_record,
            'yss_record' => $yss_record
        ]);
    }

    private function checkPermissionUpdateAccountApi($account_id)
    {
        $accountModel = new Account();
        $currentAccountId = Auth::user()->account_id;
        if ($accountModel->isAgency($currentAccountId)) {
            if ($currentAccountId === $account_id) {
                return false;
            }
            $record = $accountModel::where('agent_id', $currentAccountId)->where('account_id', $account_id)->get();
            if (!$record->isEmpty()) {
                return true;
            }
        }
        if ($currentAccountId === $account_id) {
            return true;
        }
        return false;
    }
}
