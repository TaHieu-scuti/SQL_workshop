<?php

use Illuminate\Database\Seeder;

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
            DB::table('repo_yss_accounts')->insert([
                'accountid' => rand(1, 1000),
                'account_id' => $yssAccountReport->account_id,
                'accountName' => str_random(10),
                'accountType' => str_random(10),
                'accountStatus' => 'enabled',
                'deliveryStatus' => 'enabled',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
