<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePromocionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'       => ['required','string','max:120'],
            'descuento'    => ['required','numeric','min:0','max:100'],
            'fecha_inicio' => ['required','date'],
            'fecha_fin'    => ['required','date','after_or_equal:fecha_inicio'],
            'productos'    => ['nullable','array'],
            'productos.*'  => ['integer','exists:productos,id'],
        ];
    }
}
