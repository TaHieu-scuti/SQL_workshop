<?php

use Illuminate\Database\Seeder;

// @codingStandardsIgnoreLine
class RepoYdnAccountGenerator extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $yssAccountReports = App\Model\RepoYssAccountReportCost::query()
            ->select(['account_id', 'accountid'])
            ->distinct()
            ->get();

        foreach ($yssAccountReports as $yssAccountReport) {
            $account = new \App\Model\RepoYdnAccount();
            $account->accountId = $yssAccountReport->accountid;
            $account->account_id = $yssAccountReport->account_id;
            $account->accountName = 'Ydn ' . str_random(10);
            $account->accountType = str_random(10);
            $account->accountStatus = 'enabled';
            $account->deliveryStatus = 'enabled';
            $account->created_at = date('Y-m-d H:i:s');
            $account->updated_at = date('Y-m-d H:i:s');
            $account->saveOrFail();
        }
    }
}
