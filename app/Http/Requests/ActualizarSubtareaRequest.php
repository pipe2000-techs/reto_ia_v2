<?php

namespace App\Http\Requests;

use App\Models\Tarea;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActualizarSubtareaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo'          => ['sometimes', 'required', 'string', 'max:150'],
            'descripcion'     => ['nullable', 'string'],
            'prioridad'       => ['sometimes', 'required', Rule::in(Tarea::PRIORIDADES)],
            'estado'          => ['sometimes', 'required', Rule::in(Tarea::ESTADOS)],
            'horas_estimadas' => ['nullable', 'numeric', 'min:0', 'max:9999.99'],
            'tags'            => ['nullable', 'array'],
            'tags.*'          => ['integer', 'exists:tags,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'El título de la subtarea es obligatorio.',
            'titulo.max'      => 'El título no puede superar 150 caracteres.',
            'prioridad.in'    => 'La prioridad debe ser: baja, media o alta.',
            'estado.in'       => 'El estado no es válido.',
            'tags.*.exists'   => 'Uno o más tags seleccionados no existen.',
        ];
    }
}
