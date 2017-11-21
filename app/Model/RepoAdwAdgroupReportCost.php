<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;

class RepoAdwAdgroupReportCost extends Model
{
    protected $table = 'repo_adw_adgroup_report_cost';
    public $timestamps = false;

    public function getAllAdwAdgroup()
    {
        $adwAdgroup = self::select('adGroupID as adgroupID', 'adgroupName')
            ->where('account_id', '=', Auth::user()->account_id)->get();

        return $adwAdgroup;
    }
}
