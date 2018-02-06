<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\AbstractReportModel;

class RepoPhoneTimeUse extends AbstractReportModel
{
    public $timestamps = false;
    protected $table = 'repo_phone_time_use';

    public function phoneTimeUse()
    {
        return $this->belongsTo('App\Model\PhoneTimeUse');
    }

    public function getPhoneTimeUseWithDayOfWeek(
        $account_id,
        $source,
        array $campaignIDs
    ) {
        return $this->distinct()
        ->select(
            'account_id',
            'utm_campaign',
            'phone_number'
        )->selectRaw('DAYNAME(time_of_call) AS `dayOfWeek`')
        ->where('repo_phone_time_use.account_id', $account_id)
        ->whereIn('repo_phone_time_use.utm_campaign', $campaignIDs)
        ->where('repo_phone_time_use.source', $source)
        ->where('repo_phone_time_use.traffic_type', 'AD')
        ->get();
    }
}
