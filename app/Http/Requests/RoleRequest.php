<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
        $id = $this->route('role')?->id;

        // valida nombre único dentro del guard web
        $unique = 'unique:roles,name';
        if ($id) $unique .= ',' . $id;
        return [
            'name'  => ['required','string','min:3','max:50','alpha_dash',$unique],
            'perms' => ['array'],
            'perms.*' => ['string'],
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del rol es obligatorio.',
            'name.alpha_dash' => 'El nombre solo puede contener letras, números, guiones y guiones bajos.',
        ];
    }
}
