<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Persona;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    // VALIDACIÓN: username (único en users.name) + email + password
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username'  => ['required','string','max:255', Rule::unique('users','name')],
            'email'     => ['required','string','email','max:255','unique:users,email'],
            'password'  => ['required','string','min:8','confirmed'],

            // Datos de Persona (opcionales)
            'nombre'    => ['nullable','string','max:120'],
            'ap'        => ['nullable','string','max:120'],
            'am'        => ['nullable','string','max:150'],
            'telefono'  => ['nullable','string','max:20'],
        ]);
    }

    // CREA Persona (si viene) + User (name = username) + asigna rol "cliente"
    protected function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $personaId = null;

            if (!empty($data['nombre']) || !empty($data['ap']) || !empty($data['am']) || !empty($data['telefono'])) {
                $persona = Persona::create([
                    'nombre'         => $data['nombre']   ?? null,
                    'ap'             => $data['ap']       ?? null,
                    'am'             => $data['am']       ?? null,
                    'telefono'       => $data['telefono'] ?? null,
                    'direcciones_id' => null,
                ]);
                $personaId = $persona->id;
            }

            $user = User::create([
                'personas_id' => $personaId,
                'name'        => $data['username'],   // nombre de usuario -> users.name
                'email'       => $data['email'],
                'password'    => Hash::make($data['password']),
                'estado'      => 'activo',
            ]);

            // Requiere HasRoles en el modelo User
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('cliente');
            }

            return $user;
        });
    }
}
