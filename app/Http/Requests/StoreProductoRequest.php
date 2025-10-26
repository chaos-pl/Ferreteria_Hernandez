<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequest extends FormRequest
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
            'nombre' => ['required','string','max:120'],
            'descripcion' => ['nullable','string'],
            'precio' => ['required','numeric','min:0'],
            'existencias' => ['required','integer','min:0'],
            'estado' => ['required','in:activo,inactivo'],
        ];
    }
}
