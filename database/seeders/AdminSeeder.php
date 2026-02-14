<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin role
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Administrador',
                'description' => 'Acceso total al sistema'
            ]
        );

        // Create user role
        $userRole = Role::firstOrCreate(
            ['slug' => 'user'],
            [
                'name' => 'Usuario',
                'description' => 'Usuario regular'
            ]
        );

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('password')
            ]
        );

        // Attach admin role
        $admin->roles()->sync($adminRole->id);

        $this->command->info('Admin user created: admin@example.com (password: password)');
    }
}
