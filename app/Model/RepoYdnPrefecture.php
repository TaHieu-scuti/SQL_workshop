<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\AbstractReportModel;

class RepoYdnPrefecture extends AbstractReportModel
{
    protected $table = 'repo_ydn_reports';

    public $timestamps = false;
}
