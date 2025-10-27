<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAsignacionRequest extends FormRequest
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
        return [
            'productos_id'   => ['required','exists:productos,id'],
            'proveedores_id' => [
                'required',
                'exists:proveedores,id',
                // par único producto-proveedor
                Rule::unique('asigna_productos_proveedores')
                    ->where(fn($q) => $q->where('productos_id', $this->input('productos_id'))),
            ],
            'sku_proveedor'  => ['nullable','string','max:80'],
            'plazo_entrega'  => ['nullable','integer','min:0','max:65535'],
        ];
    }
    public function messages(): array
    {
        return [
            'proveedores_id.unique' => 'Este proveedor ya está asignado a ese producto.',
        ];
    }
}
