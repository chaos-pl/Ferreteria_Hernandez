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

        // Módulos del sistema
        $modules = [
            'personas','categorias','direcciones','municipios','marcas','unidades',
            'proveedores','clientes','empleados','ventas','compras','productos',
            'asignaciones','users','promociones','carrito'
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

        // Crear roles
        $admin    = Role::firstOrCreate(['name' => 'admin',    'guard_name' => 'web']);
        $vendedor = Role::firstOrCreate(['name' => 'vendedor', 'guard_name' => 'web']);
        $cliente  = Role::firstOrCreate(['name' => 'cliente',  'guard_name' => 'web']);

        // Admin tiene todos los permisos
        $admin->givePermissionTo(Permission::all());

        // Helper para evitar errores si un permiso no existe
        $existing = Permission::pluck('name')->all();
        $only = fn(array $arr) => array_values(array_intersect($arr, $existing));

        // PERMISOS PARA VENDEDOR
        $vendedor->syncPermissions($only([
            // Productos
            'productos.viewAny','productos.view','productos.create','productos.update',

            // Categorías
            'categorias.viewAny','categorias.view',

            // Ventas
            'ventas.viewAny','ventas.view','ventas.create',

            // Asignaciones
            'asignaciones.viewAny','asignaciones.view',

            // Clientes
            'clientes.viewAny','clientes.view',
        ]));

        // PERMISOS PARA CLIENTE (COMPRAR SIN VENDEDOR)
        $cliente->syncPermissions($only([
            // Ver catálogo
            'productos.viewAny','productos.view',
            'categorias.viewAny','categorias.view',

            // Carrito
            'carrito.viewAny','carrito.view',
            'carrito.create','carrito.update','carrito.delete',

            // Ventas (solo crear su propia compra)
            'ventas.create',
        ]));

        // Crear un super admin de prueba
        $root = User::firstOrCreate(
            ['email' => 'admin@demo.test'],
            ['name' => 'Admin Demo', 'password' => bcrypt('password')]
        );
        $root->syncRoles(['admin']);
    }
}
