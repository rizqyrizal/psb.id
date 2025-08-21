<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserSetting;

class UserSettingSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            if ($user->userSetting === null) {
                UserSetting::create([
                    'user_id' => $user->id,
                    'theme' => 'light',
                    'receive_notifications' => true,
                ]);
            }
        }
    }
}
