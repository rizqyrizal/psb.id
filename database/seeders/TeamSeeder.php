<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\User;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        Team::create([
            'user_id' => $user->id,
            'name' => 'Default Team',
            'personal_team' => true,
        ]);
    }
}
