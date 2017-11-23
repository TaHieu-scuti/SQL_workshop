<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\AbstractReportModel;

class RepoYdnReport extends AbstractReportModel
{
    protected $table = 'repo_ydn_reports';

    public $timestamps = false;
}
