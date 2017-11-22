<?php

use Illuminate\Database\Seeder;
use App\Model\RepoAdwAccountReportCost;

class AddingTimeZoneDataForAdwAccountReport extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adwAccounts = RepoAdwAccountReportCost::select('id')->get();
        foreach($adwAccounts as $adwAccount) {
            $adwAccount->hourOfDay = rand(0, 23);
            $adwAccount->saveOrFail();
        }
    }
}
