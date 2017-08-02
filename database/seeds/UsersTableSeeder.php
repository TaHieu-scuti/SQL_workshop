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
        $user->name = "Testing Name";
        $user->email = "kakeya@scuti.asia ";
        $user->password = "adgainer";
        $user->save();
    }
}
