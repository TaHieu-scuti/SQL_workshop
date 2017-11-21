<?php

use Illuminate\Database\Seeder;
use App\Model\RepoYssAdgroupReportCost;

class AddingTimezoneDataForAdgroupReportCost extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adgroups = RepoYssAdgroupReportCost::select('id')->get();
        foreach ($adgroups as $adgroup) {
            $adgroup->hourofday = rand(0,23);
            $adgroup->saveOrFail();
        }
    }
}
