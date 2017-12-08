<?php

namespace App\Model;

use App\AbstractReportModel;

class Agency extends AbstractReportModel
{
    /** @var bool */
    public $timestamps = false;
    protected $table = 'accounts';
}
