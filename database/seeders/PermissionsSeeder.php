<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Solo módulos que SI usarás ahora
        $modules = [
            'personas','categorias','direcciones','municipios','marcas','unidades',
            'proveedores','clientes','empleados','ventas','compras','productos','asignaciones','users',
        ];



        $actions = ['viewAny','view','create','update','delete'];

        // Crear permisos
        foreach ($modules as $m) {
            foreach ($actions as $a) {
                Permission::firstOrCreate([
                    'name' => "$m.$a",
                    'guard_name' => 'web',
                ]);
            }
        }

        // Roles base
        $admin    = Role::firstOrCreate(['name' => 'admin',    'guard_name' => 'web']);
        $vendedor = Role::firstOrCreate(['name' => 'vendedor', 'guard_name' => 'web']);
        $cliente  = Role::firstOrCreate(['name' => 'cliente',  'guard_name' => 'web']);

        // Admin con todo
        $admin->givePermissionTo(Permission::all());

        // Helper para NO reventar si algún permiso no existe
        $existing = Permission::pluck('name')->all();
        $only = fn(array $arr) => array_values(array_intersect($arr, $existing));

        // Vendedor (ejemplo) => categorías + ventas
        $vendedor->syncPermissions($only([
            'categorias.viewAny','categorias.view','categorias.create','categorias.update',
            'ventas.viewAny','ventas.view',
            // 'productos.viewAny','productos.view','productos.create','productos.update', // si decides usar productos
        ]));

        // Cliente (ejemplo) => solo ver categorías
        $cliente->syncPermissions($only([
            'categorias.viewAny','categorias.view',
            // sin carritos.* hasta que lo implementes
        ]));

        // Súper admin de prueba
        $root = User::firstOrCreate(
            ['email' => 'admin@demo.test'],
            ['name' => 'Admin Demo', 'password' => bcrypt('password')]
        );
        $root->syncRoles(['admin']);
    }
}
