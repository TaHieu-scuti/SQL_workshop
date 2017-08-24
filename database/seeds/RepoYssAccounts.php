<?php

use Illuminate\Database\Seeder;

use App\RepoYssAccount;

// @codingStandardsIgnoreLine
class RepoYssAccounts extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $yssAccountReports = App\RepoYssAccountReport::all();

        foreach ($yssAccountReports as $yssAccountReport) {
            $account = new RepoYssAccount;
            $account->accountid = rand(1, 1000);
            $account->account_id = $yssAccountReport->account_id;
            $account->accountName = str_random(10);
            $account->accountType = str_random(10);
            $account->accountStatus = 'enabled';
            $account->deliveryStatus = 'enabled';
            $account->created_at = date('Y-m-d H:i:s');
            $account->updated_at = date('Y-m-d H:i:s');
            $account->saveOrFail();
        }
    }
}
