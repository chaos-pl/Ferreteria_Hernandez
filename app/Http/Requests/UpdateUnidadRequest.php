<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUnidadRequest extends FormRequest
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
        $id = $this->unidad?->id;
        return [
            'nombre'      => ['required','string','max:50', Rule::unique('unidades','nombre')->ignore($id)],
            'abreviatura' => ['required','string','max:10', Rule::unique('unidades','abreviatura')->ignore($id)],
        ];
    }
    public function attributes(): array
    {
        return [
            'nombre' => 'nombre',
            'abreviatura' => 'abreviatura',
        ];
    }
}
