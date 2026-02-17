<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarProyectoRequest;
use App\Http\Requests\GuardarProyectoRequest;
use App\Models\Proyecto;
use App\Services\ProyectoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProyectoController extends Controller
{
    public function __construct(
        private ProyectoService $proyecto_service
    ) {}

    public function index(): View
    {
        $proyectos = $this->proyecto_service->listar();

        foreach ($proyectos as $proyecto) {
            $proyecto->progreso = $this->proyecto_service->calcularProgreso($proyecto);
        }

        return view('proyectos.index', compact('proyectos'));
    }

    public function store(GuardarProyectoRequest $request): JsonResponse
    {
        $proyecto = $this->proyecto_service->guardar($request->validated());
        $proyecto->loadCount(['tareas', 'tareas as tareas_terminadas_count' => fn($q) => $q->where('estado', 'terminada')]);
        $proyecto->progreso = $this->proyecto_service->calcularProgreso($proyecto);

        return response()->json([
            'success' => true,
            'mensaje' => 'Proyecto creado correctamente.',
            'proyecto' => $proyecto,
        ], 201);
    }

    public function show(Proyecto $proyecto): View
    {
        $tareas = $proyecto->tareas()->orderByRaw("FIELD(prioridad, 'alta', 'media', 'baja')")->orderBy('created_at', 'desc')->get();
        $progreso = $this->proyecto_service->calcularProgreso($proyecto->loadCount(['tareas', 'tareas as tareas_terminadas_count' => fn($q) => $q->where('estado', 'terminada')]));
        $horas_totales = $this->proyecto_service->calcularHorasTotales($proyecto);

        return view('proyectos.show', compact('proyecto', 'tareas', 'progreso', 'horas_totales'));
    }

    public function update(ActualizarProyectoRequest $request, Proyecto $proyecto): JsonResponse
    {
        $proyecto = $this->proyecto_service->actualizar($proyecto, $request->validated());
        $proyecto->loadCount(['tareas', 'tareas as tareas_terminadas_count' => fn($q) => $q->where('estado', 'terminada')]);
        $proyecto->progreso = $this->proyecto_service->calcularProgreso($proyecto);

        return response()->json([
            'success' => true,
            'mensaje' => 'Proyecto actualizado correctamente.',
            'proyecto' => $proyecto,
        ]);
    }

    public function destroy(Proyecto $proyecto): JsonResponse
    {
        $this->proyecto_service->eliminar($proyecto);

        return response()->json([
            'success' => true,
            'mensaje' => 'Proyecto eliminado correctamente.',
        ]);
    }
}
