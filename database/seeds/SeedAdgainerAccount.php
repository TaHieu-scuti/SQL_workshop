<?php

use Illuminate\Database\Seeder;
use App\Model\Account;

// @codingStandardsIgnoreLine
class SeedAdgainerAccount extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rfariseAccount = new Account;
        $rfariseAccount->id = 2034;
        $rfariseAccount->account_id = 'dbc087db3467fabd8d46cb04667f5eaa';
        $rfariseAccount->account_subsidiary = 'ADGAINER KK';
        $rfariseAccount->account_language = 'ja-JP';
        $rfariseAccount->account_currency = 'JPY';
        $rfariseAccount->account_time_zone = 'Asia/Tokyo';
        $rfariseAccount->sftp = 0;
        $rfariseAccount->show_number = 1;
        $rfariseAccount->api_key = '9a3e12522d7e176ea5d0aed01a61f608';
        $rfariseAccount->api_limit = 500;
        $rfariseAccount->ppc_markup = 0;
        $rfariseAccount->agent_id = 2;
        $rfariseAccount->accountName = '1298rfarise';
        $rfariseAccount->username = 'rfarise';
        $rfariseAccount->password = md5('e51a7a6467');
        $rfariseAccount->account_owner = 1;
        $rfariseAccount->account_pin = 11111;
        $rfariseAccount->account_code = 9361;
        $rfariseAccount->account_view_ppc_all = 0;
        $rfariseAccount->account_view_keywords = 1;
        $rfariseAccount->track_email = 1;
        $rfariseAccount->email = '1298@adgainer.co.jp';
        $rfariseAccount->country = 'Japan';
        $rfariseAccount->active = 1;
        $rfariseAccount->access_only = 0;
        $rfariseAccount->api_active = 0;
        $rfariseAccount->level = 3;
        $rfariseAccount->status = 'FULL';
        $rfariseAccount->phone_data_level = 0;
        $rfariseAccount->demograph_data_level = 0;
        $rfariseAccount->marin_id = NULL;
        $rfariseAccount->billing_date = 0;
        $rfariseAccount->exp_m = 0;
        $rfariseAccount->exp_y = 0;
        $rfariseAccount->date_created = '2018-03-30 03:33:45';
        $rfariseAccount->logo = 'ad-gainer-logo-v1.1-250x54px-white-bkgd-flat.png';
        $rfariseAccount->color = 'dodgerBlue';
        $rfariseAccount->offline_img = 'OfflineButton.png';
        $rfariseAccount->online_img = 'OnlineButton.png';
        $rfariseAccount->last_login = '2018-03-29 20:33:45';
        $rfariseAccount->saveOrFail();
    }
}
