<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Account;
use Auth;
use App\Http\Controllers\AbstractReportController;

class CheckRoleClient
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
        $model = new Account();
        $arrAccounts = $model->getAllAdminAndAgencyAccounts();
        if (in_array(Auth::user()->id, $arrAccounts['agency'])
            && session(AbstractReportController::SESSION_KEY_CLIENT_ID) === null) {
            return redirect('/client-report');
        }
        return $next($request);
    }
}
