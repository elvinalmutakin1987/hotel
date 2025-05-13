<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();

        if (!$adminRole) {
            $this->command->warn('Role admin not found. Please run PermissionSeeder first.');
            return;
        }

        $admin = User::firstOrCreate([
            'email' => 'elvinalmutakin@gmail.com',
        ], [
            'name' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('admin'),
        ]);

        $admin->assignRole($adminRole);
    }
}
