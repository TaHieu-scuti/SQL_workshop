<?php

use Illuminate\Database\Seeder;
use App\Model\Account;

// @codingStandardsIgnoreLine
class AccountGenerator extends Seeder
{
    const NUMBER_OF_ACCOUNTS = 12;
    const LEVEL = 3;
    private $agentId = [''];
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function processAccounts($account_id)
    {
        if ($account_id !== 1) {
            array_push($this->agentId, $account_id);
        }
        $account = new Account();
        $account->account_id = $account_id;
        $account->account_subsidiary = '';
        $account->account_language = '';
        $account->account_currency = '';
        $account->ftp_user = '';
        $account->ftp_pass = '';
        $account->ftp_folder = '';
        $account->api_key = '';
        $account->super_agent_id = '';
        $account->level = self::LEVEL;
        if ((int)$account_id < 4) {
            $account->level = 3;
        } elseif ((int)$account_id === 4) {
            $account->level = 1;
        }
        $account->agent_id = $this->agentId[mt_rand(0, count($this->agentId) - 1)];
        if ((int)$account_id === 3 || (int)$account_id === 1 || (int)$account_id === 4) {
            $account->agent_id = '';
        }
        elseif ((int)$account_id === 2) {
            $account->agent_id = $account_id + 1;
        }
        $account->accountName = str_random(10);
        $account->dept = '';
        $account->username = 'admin'.$account_id;
        $account->password = bcrypt('admin');
        $account->account_pin = 0;
        $account->account_code = 0;
        $account->email = 'admin'.$account_id.'@gmail.com';
        $account->tel = '';
        $account->companyName = '';
        $account->address = '';
        $account->address2 = '';
        $account->city = '';
        $account->state = '';
        $account->zip = '';
        $account->country = '';
        $account->contact = '';
        $account->account_mgr = '';
        $account->super_id = '';
        $account->billing_type = '';
        $account->billing_date = 0;
        $account->billing_address_1 = '';
        $account->billing_address_2 = '';
        $account->billing_city = '';
        $account->billing_state = '';
        $account->billing_zip = '';
        $account->billing_country = '';
        $account->card_number = '';
        $account->cvv = '';
        $account->exp_m = 0;
        $account->exp_y = 1;
        $account->name_on_card = '';
        $account->logo = '';
        $account->wallpaper = '';
        $account->subdomain = '';
        $account->slogan = '';
        $account->last_login = '2017-11-27 01:01:11';
        $account->last_edited = '';
        $account->adw_client_id = '';
        $account->adw_refresh_token = '';
        $account->ds_access_token = '';
        $account->ds_refresh_token = '';
        $account->saveOrFail();
    }

    public function run()
    {
        for ($account_id = 1; $account_id < self::NUMBER_OF_ACCOUNTS + 1; $account_id++) {
            $this->processAccounts($account_id);
        }
    }
}
