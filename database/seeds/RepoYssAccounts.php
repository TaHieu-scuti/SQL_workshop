<?php

use Illuminate\Database\Seeder;

use App\Model\RepoYssAccount;

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
        $yssAccountReports = App\RepoYssAccountReport::query()
            ->select(['account_id', 'accountid'])
            ->distinct()
            ->get();

        foreach ($yssAccountReports as $yssAccountReport) {
            $account = new RepoYssAccount;
            $account->accountid = $yssAccountReport->accountid;
            $account->account_id = $yssAccountReport->account_id;
            $account->accountName = 'YSS ' . str_random(10);
            $account->accountType = str_random(10);
            $account->accountStatus = 'enabled';
            $account->deliveryStatus = 'enabled';
            $account->created_at = date('Y-m-d H:i:s');
            $account->updated_at = date('Y-m-d H:i:s');
            $account->saveOrFail();
        }
    }
}
