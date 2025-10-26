<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Credenciales desde .env (mejor para no hardcodear)
        $name  = env('ADMIN_NAME', 'Admin Demo');
        $email = env('ADMIN_EMAIL', 'admin@demo.test');
        $pass  = env('ADMIN_PASSWORD', 'password');

        // Crea/asegura el usuario
        $admin = User::firstOrCreate(
            ['email' => $email],
            [
                'name'          => $name,
                'password'      => Hash::make($pass),
                'estado'        => 'activo',     // si tu users tiene este campo
                'personas_id'   => null,         // si aplica
                'email_verified_at' => now(),
            ]
        );

        // Asegura rol admin (guard web)
        $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        // Asigna el rol
        $admin->syncRoles([$role]);
    }
}
