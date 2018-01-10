<?php

use Illuminate\Database\Seeder;

use App\Model\PhoneTimeUse;
use App\Model\RepoPhoneTimeUse;

// @codingStandardsIgnoreLine
class RepoPhoneTimeUseGenerator extends Seeder
{
    const MOBILES = [
        'iOS / Safari / 602.1',
        'Android / Chrome / 58.0.3029.83',
        'Symbian OS /  / ',
        'BlackBerry / Safari / 534.11',
        'Windows Phone / Spartan / 15.15063',
    ];

    const TABLETS_AND_PC = [
        'Windows XP / Chrome / 49.0.2623.112',
        'Windows 10 / Internet Explorer / 11.0',
        'Windows 7 / Internet Explorer / 11.0',
        'Windows 8.1 / Internet Explorer / 11.0',
        'Windows 8 / Firefox / 24.0',
        'Mac OS X / Safari / 602.3.12',
        'Linux / Chrome / 40.0.2214.89',
        'Windows Vista / Chrome / 49.0.2623.112',
        'Unknown Platform / Mozilla / 4.0',
        'FreeBSD / Safari / 534.30',
        'Unknown Windows OS / Chrome / 41.0.2225.0',
        'NetBSD / Firefox / 54.0',
        'Windows 2000 / Opera / 12.18',
        'Windows NT 4.0 / Chrome / 49.0.2623.112',
        'Windows 2003 / Firefox / 47.0',
        'Windows 98 / Opera / 12.16'
    ];

    public function createRecord()
    {
        $phoneTimeUse = new PhoneTimeUse;
        $phoneTimeUse = $phoneTimeUse->all();

        foreach ($phoneTimeUse as $key => $value) {
            $repoPhoneTimeUse = new RepoPhoneTimeUse;
            $repoPhoneTimeUse->phone_time_use_id = $value->id;
            $repoPhoneTimeUse->visitor_city_state = $value->visitor_city_state;

            if ($value->mobile === 'Yes') {
                $repoPhoneTimeUse->platform = self::MOBILES[rand(0, (count(self::MOBILES))-1)];
            } else {
                $repoPhoneTimeUse->platform = self::TABLETS_AND_PC[rand(0, (count(self::TABLETS_AND_PC))-1)];
            }

            $repoPhoneTimeUse->account_id = $value->account_id;
            $repoPhoneTimeUse->campaign_id = $value->campaign_id;
            $repoPhoneTimeUse->utm_campaign = $value->utm_campaign;
            $repoPhoneTimeUse->time_of_call = $value->time_of_call;
            $repoPhoneTimeUse->source = $value->source;
            $repoPhoneTimeUse->phone_number = $value->phone_number;
            $repoPhoneTimeUse->traffic_type = $value->traffic_type;
            $repoPhoneTimeUse->mobile = $value->mobile;

            $repoPhoneTimeUse->saveOrFail();
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createRecord();
    }
}
