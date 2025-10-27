<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Persona;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:users.viewAny')->only('index');
        $this->middleware('permission:users.view')->only('show');
        $this->middleware('permission:users.create')->only(['create','store']);
        $this->middleware('permission:users.update')->only(['edit','update']);
        $this->middleware('permission:users.delete')->only('destroy');
    }
    public function index(Request $request)
    {
        $q       = trim($request->get('q', ''));
        $perPage = (int)($request->integer('per_page') ?: 10);

        $users = User::query()
            ->with(['persona'])
            ->when($q, function ($query) use ($q) {
                $query->where(function($s) use ($q) {
                    $s->where('name','like',"%{$q}%")
                        ->orWhere('email','like',"%{$q}%");
                });
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.users.index', [
            'users'   => $users,
            'q'       => $q,
            'perPage' => $perPage,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create', [
            'personas' => Persona::orderBy('nombre')->get(['id','nombre']),
            'roles'    => Role::orderBy('name')->get(['id','name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $user = new User();
        $user->fill([
            'personas_id' => $data['personas_id'] ?? null,
            'name'        => $data['name'],
            'email'       => $data['email'],
            'password'    => Hash::make($data['password']),
            'estado'      => $data['estado'],
        ]);
        if (!empty($data['verified'])) {
            $user->email_verified_at = now();
        }
        $user->save();

        if (!empty($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return redirect()
            ->route('admin.users.show', $user)
            ->with('status', 'Usuario creado');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['persona','roles']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user->load('roles');
        return view('admin.users.edit', [
            'user'     => $user,
            'personas' => Persona::orderBy('nombre')->get(['id','nombre']),
            'roles'    => Role::orderBy('name')->get(['id','name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        $user->fill([
            'personas_id' => $data['personas_id'] ?? null,
            'name'        => $data['name'],
            'email'       => $data['email'],
            'estado'      => $data['estado'],
        ]);

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->email_verified_at = !empty($data['verified']) ? now() : null;

        $user->save();

        if (array_key_exists('roles', $data)) {
            $user->syncRoles($data['roles'] ?? []);
        }

        return redirect()
            ->route('admin.users.show', $user)
            ->with('status', 'Usuario actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
// Evita que un usuario se borre a sí mismo
        if ((int)auth()->id() === (int)$user->id) {
            return back()->with('status', 'No puedes eliminar tu propio usuario.');
        }

        try {
            $user->delete();
            $msg = 'Usuario eliminado';
        } catch (QueryException $e) {
            // Si hay FKs (ventas) que impiden el delete, lo desactivamos:
            $user->update(['estado' => 'inactivo']);
            $msg = 'El usuario tiene movimientos, se desactivó el estado.';
        }

        return redirect()->route('admin.users.index')->with('status', $msg);
    }
}
