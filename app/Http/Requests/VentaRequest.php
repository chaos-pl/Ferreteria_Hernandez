<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'users_id'   => ['required','exists:users,id'],
            'estado'     => ['required','in:pagada,cancelada'],
            'descuentos' => ['nullable','numeric','min:0'],
            'impuestos'  => ['nullable','numeric','min:0'],

            // Validación de detalles
            'app_id'        => ['required','array','min:1'],
            'app_id.*'      => ['required','integer','exists:asigna_productos_proveedores,id'],

            'cantidad'      => ['required','array','min:1'],
            'cantidad.*'    => ['required','integer','min:1'],

            'precio_unit'   => ['required','array','min:1'],
            'precio_unit.*' => ['required','numeric','min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'app_id.required' => 'Debe seleccionar al menos un producto.',
            'app_id.*.exists' => 'Alguna asignación producto–proveedor no existe.',
        ];
    }

}
