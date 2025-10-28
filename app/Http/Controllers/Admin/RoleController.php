<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:roles.viewAny')->only(['index']);
        $this->middleware('permission:roles.create')->only(['create','store']);
        $this->middleware('permission:roles.view')->only(['show']);
        $this->middleware('permission:roles.update')->only(['edit','update']);
        $this->middleware('permission:roles.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $q       = trim((string)$request->get('q',''));
        $perPage = (int)$request->get('per_page', 10);

        $roles = Role::where('guard_name','web')
            ->when($q !== '', fn($qq)=>$qq->where('name','like',"%{$q}%"))
            ->orderBy('name')
            ->paginate($perPage)->withQueryString();

        return view('admin.roles.index', compact('roles','q','perPage'));
    }

    public function create()
    {
        $perms = Permission::where('guard_name','web')
            ->orderBy('name')->get()
            ->groupBy(fn($p)=> Str::before($p->name,'.')); // agrupa por módulo

        $role = new Role(['guard_name' => 'web']);
        return view('admin.roles.create', compact('role','perms'));
    }

    public function store(RoleRequest $request)
    {
        $role = Role::create([
            'name'       => $request->name,
            'guard_name' => 'web',
        ]);

        // Asigna permisos seleccionados (agrega sin borrar otros)
        $role->givePermissionTo($request->input('perms', []));

        return redirect()->route('admin.roles.index')
            ->with('status','Rol creado correctamente');
    }

    public function show(Role $role)
    {
        abort_unless($role->guard_name === 'web', 404);

        $role->load('permissions');
        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        abort_unless($role->guard_name === 'web', 404);

        $perms = Permission::where('guard_name','web')
            ->orderBy('name')->get()
            ->groupBy(fn($p)=> Str::before($p->name,'.'));

        $assigned = $role->permissions()->pluck('name')->all();

        return view('admin.roles.edit', compact('role','perms','assigned'));
    }

    public function update(RoleRequest $request, Role $role)
    {
        abort_unless($role->guard_name === 'web', 404);

        // Evita renombrar 'admin' si quieres protegerlo (opcional)
        if ($role->name !== 'admin') {
            $role->name = $request->name;
            $role->save();
        }

        // Reemplaza set de permisos por los seleccionados
        $role->syncPermissions($request->input('perms', []));

        return redirect()->route('admin.roles.edit',$role)
            ->with('status','Rol actualizado');
    }

    public function destroy(Role $role)
    {
        abort_unless($role->guard_name === 'web', 404);

        if ($role->name === 'admin') {
            return back()->with('status','No puedes eliminar el rol admin.');
        }

        // Desasocia relaciones y elimina
        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('status','Rol eliminado');
    }
}
