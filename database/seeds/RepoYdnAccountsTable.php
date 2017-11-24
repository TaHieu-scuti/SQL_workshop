<?php

use Illuminate\Database\Seeder;

// @codingStandardsIgnoreLine
class RepoYdnAccountsTable extends Seeder
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
            . __DIR__ . '/../../database/resources/repo_ydn_accounts.sql';

        exec($command);
    }
}
