<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonaRequest extends FormRequest
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
            'nombre'        => ['required','string','max:120'],
            'ap'            => ['nullable','string','max:120'],
            'am'            => ['nullable','string','max:150'],
            'telefono'      => ['nullable','string','max:20'],
            'direcciones_id'=> ['nullable','integer','exists:direcciones,id'],
        ];
    }
}
