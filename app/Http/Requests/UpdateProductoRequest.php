<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductoRequest extends FormRequest
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
            'nombre'        => ['required','string','max:150'],
            'descripcion'   => ['nullable','string'],
            'categorias_id' => ['required','integer','exists:categorias,id'],
            'unidades_id'   => ['required','integer','exists:unidades,id'],
            'marcas_id'     => ['nullable','integer','exists:marcas,id'],
            'precio'        => ['required','numeric','min:0','max:99999999.99'],
            'existencias'   => ['required','integer','min:0'],
            'estado'        => ['required', Rule::in(['activo','inactivo'])],
            'imagen'        => ['nullable','image','max:2048'],
        ];
    }
    public function attributes(): array
    {
        return ['categorias_id'=>'categoría','unidades_id'=>'unidad','marcas_id'=>'marca'];
    }
}
