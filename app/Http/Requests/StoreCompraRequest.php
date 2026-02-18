<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proveedores_id' => ['required','integer','exists:proveedores,id'],

            // Ya NO se envían fecha_compra ni estado
            // La fecha se genera en el controlador: now()
            // El estado siempre será "ordenada"

            // Detalles
            'asigna_ids'   => ['required','array','min:1'],
            'asigna_ids.*' => ['required','integer','exists:asigna_productos_proveedores,id'],

            'cantidades'   => ['required','array','min:1'],
            'cantidades.*' => ['required','integer','min:1'],

            'costos'       => ['required','array','min:1'],
            'costos.*'     => ['required','numeric','min:0'],
        ];
    }
}
