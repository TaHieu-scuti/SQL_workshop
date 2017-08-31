<?php

use Illuminate\Database\Seeder;

use App\User;

// @codingStandardsIgnoreLine
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();

        $user->email = "info@example.com";
        $user->password = 'admin';
        $user->username = 'admin';
        $user->firstName = 'Ad';
        $user->lastName = 'Gainer';
        $user->account_owner = 1;
        $user->write = 1;
        $user->language = 'en-US';
        $user->currency = 'USD';
        $user->manager = '';
        $user->phone_company = '+84123456789';
        $user->phone_number = '+84123456789';
        $user->photo = 'photo.jpg';
        $user->chat_user = 0;
        $user->dept = 'Sales';
        $user->type = 1;
        $user->active = 1;
        $user->account_id = '1';
        $user->attach_accounts = '';
        $user->level = 7;
        $user->last_login = '2017-02-03 01:00:00';
        $user->chat_time = '2017-02-03 01:00:00';
        $user->internal_chat_time = '2017-02-01 02:00:00';
        $user->session_key = '';
        $user->color = 'FFFFFF';
        $user->wallpaper = '';
        $user->adw_client_id = '925-573-7287';
        $user->adw_refresh_token = '{"client_id":"655668221369-ltnvmrqaqbcinvki'
            . '85jmvadoujjbgl6c.apps.googleusercontent.com","client_secret":"mS'
            . 'yUQAwTQPFg-Z5AqxPrRhPr","refresh_token":"1\/J8InjEfhud149RoribB9'
            . 'pd48U0Q2eY_hovAcxLqaxFE","access_token":"ya29.FQIqV5o5jc3LOpknmM'
            . 'o6b5IWEke_VjNvjzOBycwnA-hQDYAv2meYo1_E2oBCBm8wkE8p","token_type"'
            . ':"Bearer","expires_in":3600,"timestamp":1445572938}';
        $user->ds_access_token = '';
        $user->ds_refresh_token = '{"access_token":"ya29.0QHt2LddwnwDoQM2Bc6Be7'
            . 'TWi-4W_CpYM74gz5FqEVPAcZPh98bFqJNmOmD0Oy6VkRcM","token_type":"Be'
            . 'arer","expires_in":3600,"refresh_token":"1\/lMEFJORxmrbVL7CeCvRd'
            . 'vJ67FYnzjbTRvt7JCh0VI54","created":1439726389}';

        $user->saveOrFail();
    }
}
