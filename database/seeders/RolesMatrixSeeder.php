<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesMatrixSeeder extends Seeder
{
    private function perm(string $name): Permission
    {
        return Permission::firstOrCreate(['name'=>$name,'guard_name'=>'web']);
    }

    private function ensureSet(string $module): array
    {
        $names = ['viewAny','view','create','update','delete'];
        $out = [];
        foreach ($names as $n) {
            $out[$n] = $this->perm("$module.$n");
        }
        return $out;
    }

    public function run(): void
    {
        // 1) Limpia caché
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // 2) Asegura módulos según tu matriz/código
        $modules = [
            'users','personas','productos','categorias','marcas','unidades','proveedores',
            'asignaciones',            // mapea a asigna_productos_proveedores en tu app
            'compras','ventas',
            'venta_detalles',          // si vas a proteger detalles también
            'carritos','carrito_items',
            'direcciones','municipios',
            'roles',                   // para tu CRUD de roles
        ];
        foreach ($modules as $m) $this->ensureSet($m);

        // 3) Roles
        $admin   = Role::firstOrCreate(['name'=>'admin','guard_name'=>'web']);
        $rv      = Role::firstOrCreate(['name'=>'control_ventas','guard_name'=>'web']);
        $rc      = Role::firstOrCreate(['name'=>'control_compras','guard_name'=>'web']);
        $cliente = Role::firstOrCreate(['name'=>'cliente','guard_name'=>'web']);

        // Helper: mapa S/I/U/D
        $map = fn($m, $S=false,$I=false,$U=false,$D=false) =>
        array_values(array_filter([
            $S ? "viewAny" : null, $S ? "view" : null,
            $I ? "create" : null,  $U ? "update" : null, $D ? "delete" : null
        ]));

        // Admin = TODO (solo guard web)
        $admin->syncPermissions(
            Permission::where('guard_name','web')->pluck('name')->all()
        );

        // Control_Ventas
        $rvPerms = [];
        foreach (['users','personas','productos','categorias','marcas','unidades','proveedores','asignaciones','compras','direcciones','municipios'] as $m) {
            foreach ($map($m, true,false,false,false) as $a) $rvPerms[]="$m.$a";
        }
        foreach (['ventas','venta_detalles','carritos','carrito_items'] as $m) {
            foreach ($map($m, true,true,true,true) as $a) $rvPerms[]="$m.$a";
        }
        $rvPerms = array_values(array_unique($rvPerms));
        $rv->syncPermissions(Permission::whereIn('name',$rvPerms)->get());

        // Control_Compras
        $rcPerms = [];
        foreach (['users','personas','productos','categorias','marcas','unidades','municipios'] as $m) {
            foreach ($map($m, true,false,false,false) as $a) $rcPerms[]="$m.$a";
        }
        foreach (['proveedores','asignaciones','compras','direcciones'] as $m) {
            foreach ($map($m, true,true,true,true) as $a) $rcPerms[]="$m.$a";
        }
        $rcPerms = array_values(array_unique($rcPerms));
        $rc->syncPermissions(Permission::whereIn('name',$rcPerms)->get());

        // Cliente
        $clPerms = [];
        foreach (['productos','categorias','marcas','unidades','municipios'] as $m) {
            foreach ($map($m, true,false,false,false) as $a) $clPerms[]="$m.$a";
        }
        foreach ($map('ventas', true,true,false,false) as $a) $clPerms[]="ventas.$a";
        foreach (['carritos','carrito_items','direcciones'] as $m) {
            foreach ($map($m, true,true,true,true) as $a) $clPerms[]="$m.$a";
        }
        $clPerms = array_values(array_unique($clPerms));
        $cliente->syncPermissions(Permission::whereIn('name',$clPerms)->get());

        // 4) Limpia caché otra vez
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
