<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAsignacionRequest extends FormRequest
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
        $id = $this->route('asignacion')?->id;
        return [
            'productos_id'   => ['required','exists:productos,id'],
            'proveedores_id' => [
                'required',
                'exists:proveedores,id',
                Rule::unique('asigna_productos_proveedores')
                    ->where(fn($q) => $q->where('productos_id', $this->input('productos_id')))
                    ->ignore($id),
            ],
            'sku_proveedor'  => ['nullable','string','max:80'],
            'plazo_entrega'  => ['nullable','integer','min:0','max:65535'],
        ];
    }
}
