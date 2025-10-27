<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompraRequest extends FormRequest
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
            'proveedores_id' => ['required','integer','exists:proveedores,id'],
            'fecha_compra'   => ['required','date'],
            'estado'         => ['required','in:borrador,ordenada,recibida,cancelada'],

            // Detalles
            'asigna_ids'     => ['required','array','min:1'],
            'asigna_ids.*'   => ['required','integer','exists:asigna_productos_proveedores,id'],
            'cantidades'     => ['required','array','min:1'],
            'cantidades.*'   => ['required','integer','min:1'],
            'costos'         => ['required','array','min:1'],
            'costos.*'       => ['required','numeric','min:0'],
        ];
    }
}
