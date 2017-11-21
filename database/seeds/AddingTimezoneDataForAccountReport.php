<?php

use Illuminate\Database\Seeder;
use App\Model\RepoYssAccountReportCost;

// @codingStandardsIgnoreLine
class AddingTimezoneDataForAccountReport extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accounts = RepoYssAccountReportCost::select('id')->get();
        foreach ($accounts as $account) {
            $account->hourofday = rand(0, 23);
            $account->saveOrFail();
        }
    }
}
