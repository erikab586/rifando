<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;

class AssignAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:assign-admin-role {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign admin role to a user by email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("Usuario con email $email no encontrado");
            return 1;
        }

        $adminRole = Role::where('slug', 'admin')->first();

        if (!$adminRole) {
            $this->error('Rol admin no encontrado');
            return 1;
        }

        // Detach all roles first
        $user->roles()->detach();
        
        // Attach admin role
        $user->roles()->attach($adminRole);

        $this->info("Rol admin asignado a $email exitosamente");
        return 0;
    }
}
