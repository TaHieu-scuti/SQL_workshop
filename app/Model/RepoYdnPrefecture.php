<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\AbstractReportModel;

class RepoYdnPrefecture extends AbstractReportModel
{
    protected $table = 'repo_ydn_reports';

    protected $timestamps = 'false';
}
