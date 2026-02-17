<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarTareaRequest;
use App\Http\Requests\GuardarTareaRequest;
use App\Models\Proyecto;
use App\Models\Tarea;
use App\Services\TareaService;
use Illuminate\Http\JsonResponse;

class TareaController extends Controller
{
    public function __construct(
        private TareaService $tarea_service
    ) {}

    public function store(GuardarTareaRequest $request, Proyecto $proyecto): JsonResponse
    {
        $datos = array_merge($request->validated(), ['proyecto_id' => $proyecto->id]);
        $tarea = $this->tarea_service->guardar($datos);

        return response()->json([
            'success' => true,
            'mensaje' => 'Tarea creada correctamente.',
            'tarea'   => $tarea,
        ], 201);
    }

    public function update(ActualizarTareaRequest $request, Tarea $tarea): JsonResponse
    {
        try {
            $tarea = $this->tarea_service->actualizar($tarea, $request->validated());

            return response()->json([
                'success' => true,
                'mensaje' => 'Tarea actualizada correctamente.',
                'tarea'   => $tarea,
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
        $this->tarea_service->eliminar($tarea);

        return response()->json([
            'success' => true,
            'mensaje' => 'Tarea eliminada correctamente.',
        ]);
    }
}
