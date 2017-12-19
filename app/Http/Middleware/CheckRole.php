<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Account;
use App\Http\Controllers\AbstractReportController;
use Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // TODO: add the authorization code, will do in next PR
        $model = new Account();
        $arrAccounts = $model->getAllAdminAndAgencyAccounts();

        if (in_array(Auth::user()->id, $arrAccounts['agency'])) {
            session([AbstractReportController::SESSION_KEY_AGENCY_ID => Auth::user()->id]);
            return redirect('/client-report');
        } elseif (!in_array(Auth::user()->id, $arrAccounts['agency'])
            && !in_array(Auth::user()->id, $arrAccounts['admin'])
        ) {
            session([AbstractReportController::SESSION_KEY_CLIENT_ID => Auth::user()->id]);
            return redirect('/account_report');
        }
        return $next($request);
    }
}
