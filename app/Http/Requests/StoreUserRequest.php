<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'personas_id' => ['nullable','integer','exists:personas,id'],
            'name'        => ['required','string','max:255'],
            'email'       => ['required','email','max:255','unique:users,email'],
            'password'    => ['required','string','min:8','confirmed'],
            'estado'      => ['required','in:activo,inactivo'],
            'verified'    => ['nullable','boolean'],

            'roles'       => ['nullable','array'],
            'roles.*'     => ['string','exists:roles,name'],
        ];
    }
}
