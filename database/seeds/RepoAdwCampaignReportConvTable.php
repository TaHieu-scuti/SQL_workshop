<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

// @codingStandardsIgnoreLine
class RepoAdwCampaignReportConvTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $command = 'mysql -h'
            . Config::get('database.connections.mysql.host')
            . ' -u'
            . Config::get('database.connections.mysql.username')
            . ' -p'
            . Config::get('database.connections.mysql.password')
            . ' '
            . Config::get('database.connections.mysql.database')
            . ' < '
            . __DIR__ . '/../../database/resources/repo_adw_campaign_report_conv.sql';

        exec($command);
    }
}
