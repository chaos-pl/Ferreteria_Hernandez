<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user')->id;
        return [
            'personas_id' => ['nullable','integer','exists:personas,id'],
            'name'        => ['required','string','max:255'],
            'email'       => ['required','email','max:255', Rule::unique('users','email')->ignore($userId)],
            'password'    => ['nullable','string','min:8','confirmed'],
            'estado'      => ['required','in:activo,inactivo'],
            'verified'    => ['nullable','boolean'],

            'roles'       => ['nullable','array'],
            'roles.*'     => ['string','exists:roles,name'],
        ];
    }
}
