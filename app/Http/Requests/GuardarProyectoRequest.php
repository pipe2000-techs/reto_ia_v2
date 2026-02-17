<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarProyectoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'       => ['required', 'string', 'max:150'],
            'descripcion'  => ['nullable', 'string'],
            'fecha_limite' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del proyecto es obligatorio.',
            'nombre.max'      => 'El nombre no puede superar 150 caracteres.',
            'fecha_limite.date' => 'La fecha límite no es válida.',
        ];
    }
}
