<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear rol Admin si no existe
        Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Administrador',
                'description' => 'Acceso total al sistema',
                'permissions' => [
                    'view_dashboard',
                    'manage_rifas',
                    'manage_users',
                    'manage_roles',
                    'manage_cupones',
                    'view_compras',
                ],
            ]
        );

        // Crear rol Vendedor si no existe
        Role::firstOrCreate(
            ['slug' => 'vendedor'],
            [
                'name' => 'Vendedor',
                'description' => 'Acceso limitado a sus rifas asignadas',
                'permissions' => [
                    'view_assigned_rifas',
                    'view_rifa_statistics',
                    'view_assigned_compras',
                ],
            ]
        );
    }
}

