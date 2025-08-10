<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission; // Pastikan ini ada

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'guru']);
        Role::firstOrCreate(['name' => 'siswa']);

        // --- Bagian Penting ---
        // Memberikan SEMUA permission yang ada ke role 'admin'
        // Jika permissions belum dibuat, ini tidak akan berhasil.
        $adminRole->givePermissionTo(Permission::all());
    }
}