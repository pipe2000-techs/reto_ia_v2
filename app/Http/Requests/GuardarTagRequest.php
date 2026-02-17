<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:50', 'unique:tags,nombre'],
            'color'  => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del tag es obligatorio.',
            'nombre.max'      => 'El nombre no puede superar 50 caracteres.',
            'nombre.unique'   => 'Ya existe un tag con ese nombre.',
            'color.required'  => 'El color es obligatorio.',
            'color.regex'     => 'El color debe ser un código hexadecimal válido (ej: #FF5733).',
        ];
    }
}
