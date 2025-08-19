<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan role 'admin' ada
        Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web']
        );

        // Buat user baru atau temukan jika sudah ada
        $user = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'admin',
                'password' => bcrypt('1234512345'),
            ]
        );

        // Berikan role 'admin' ke user ini
        $user->assignRole('admin');
    }
}
