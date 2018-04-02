<?php

namespace App\Model;

use App\AbstractReportModel;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;

class PhoneTimeUse extends AbstractReportModel
{
    public $timestamps = false;
    protected $table = 'phone_time_use';

    public function repoPhoneTimeUse()
    {
        return $this->hasOne('App\Model\RepoPhoneTimeUse');
    }

    public function getPhoneTimeUseId($startDay, $endDay, $engine)
    {
        return $this->select('count(id) AS id', 'account_id')
            ->where(
                function (EloquentBuilder $query) use ($startDay, $endDay) {
                    $this->addConditionForDate($query, $this->getTable(), $startDay, $endDay);
                }
            )
            ->where('source', $engine)
            ->where('traffic_type', 'AD')
            ->groupBy('account_id');
    }
}
