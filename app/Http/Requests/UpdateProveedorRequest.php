<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProveedorRequest extends FormRequest
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
            'personas_id' => ['required','integer','exists:personas,id'],
            'estado'      => ['required', Rule::in(['activo','inactivo'])],
        ];
    }
    public function attributes(): array
    {
        return [
            'personas_id' => 'persona',
            'estado'      => 'estado',
        ];
    }
}
