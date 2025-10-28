<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VentaRequest extends FormRequest
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
            'users_id'     => ['required','exists:users,id'],
            'fecha_venta'  => ['required','date'],
            'estado'       => ['required','in:carrito,pagada,enviada,entregada,cancelada'],
            'descuentos'   => ['nullable','numeric','min:0'],
            'impuestos'    => ['nullable','numeric','min:0'],

            // Detalles (arrays)
            'app_id'        => ['array'],
            'app_id.*'      => ['integer','exists:asigna_productos_proveedores,id','distinct'], // <-- clave
            'cantidad'      => ['array'],
            'cantidad.*'    => ['nullable','integer','min:1'],
            'precio_unit'   => ['array'],
            'precio_unit.*' => ['nullable','numeric','min:0'],
        ];
    }
    public function messages(): array
    {
        return [
            'app_id.*.exists' => 'Alguna asignación producto–proveedor no existe.',
        ];
    }
}
