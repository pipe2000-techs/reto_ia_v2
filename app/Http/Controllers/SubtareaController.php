<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarSubtareaRequest;
use App\Http\Requests\GuardarSubtareaRequest;
use App\Models\Tarea;
use App\Services\SubtareaService;
use Illuminate\Http\JsonResponse;

class SubtareaController extends Controller
{
    public function __construct(
        private SubtareaService $subtarea_service
    ) {}

    public function store(GuardarSubtareaRequest $request, Tarea $tarea): JsonResponse
    {
        try {
            $subtarea = $this->subtarea_service->guardar($request->validated(), $tarea);

            return response()->json([
                'success'  => true,
                'mensaje'  => 'Subtarea creada correctamente.',
                'subtarea' => $subtarea,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'mensaje' => $e->errors()['tarea_padre_id'][0] ?? 'Error de validación.',
            ], 422);
        }
    }

    public function update(ActualizarSubtareaRequest $request, Tarea $tarea): JsonResponse
    {
        try {
            $subtarea = $this->subtarea_service->actualizar($tarea, $request->validated());

            return response()->json([
                'success'  => true,
                'mensaje'  => 'Subtarea actualizada correctamente.',
                'subtarea' => $subtarea,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'mensaje' => $e->errors()['estado'][0] ?? 'Error de validación.',
            ], 422);
        }
    }

    public function destroy(Tarea $tarea): JsonResponse
    {
        $this->subtarea_service->eliminar($tarea);

        return response()->json([
            'success' => true,
            'mensaje' => 'Subtarea eliminada correctamente.',
        ]);
    }
}
