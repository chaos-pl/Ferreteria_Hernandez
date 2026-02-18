<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Persona;
use App\Models\Direccion;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Municipio;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    // ==============================
    // VALIDACIÓN COMPLETA
    // ==============================
    public function showRegistrationForm()
    {
        $municipios = Municipio::orderBy('nombre')->get();
        return view('auth.register', compact('municipios'));
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            // USUARIO
            'username' => ['required','string','max:255', Rule::unique('users','name')],
            'email'    => ['required','string','email','max:255','unique:users,email'],
            'password' => ['required','string','min:8','confirmed'],

            // PERSONA
            'nombre'   => ['required','string','max:120'],
            'ap'       => ['required','string','max:120'],
            'am'       => ['required','string','max:150'],
            'telefono' => ['required','string','max:20'],

            // DIRECCIÓN (según BD real)
            'calle'          => ['required','string','max:100'],
            'colonia'        => ['required','string','max:100'],
            'municipios_id'  => ['required','integer','exists:municipios,id'],
        ]);
    }

    // ==============================
    // CREAR DIRECCIÓN + PERSONA + USUARIO
    // ==============================
    protected function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            // 1. Registrar dirección (BD real NO tiene 'numero', 'estado', 'cp')
            $dir = Direccion::create([
                'calle'         => $data['calle'],
                'colonia'       => $data['colonia'],
                'municipios_id' => $data['municipios_id'],
            ]);

            // 2. Registrar persona
            $persona = Persona::create([
                'nombre'         => $data['nombre'],
                'ap'             => $data['ap'],
                'am'             => $data['am'],
                'telefono'       => $data['telefono'],
                'direcciones_id' => $dir->id,
            ]);

            // 3. Registrar usuario
            $user = User::create([
                'personas_id' => $persona->id,
                'name'        => $data['username'],
                'email'       => $data['email'],
                'password'    => Hash::make($data['password']),
                'estado'      => 'activo',
            ]);

            if (method_exists($user, 'assignRole')) {
                $user->assignRole('cliente');
            }

            return $user;
        });
    }
}
