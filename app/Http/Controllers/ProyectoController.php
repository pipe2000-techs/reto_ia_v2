<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarProyectoRequest;
use App\Http\Requests\GuardarProyectoRequest;
use App\Models\Proyecto;
use App\Services\ProyectoService;
use App\Services\TagService;
use App\Services\TareaService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ProyectoController extends Controller
{
    public function __construct(
        private ProyectoService $proyecto_service,
        private TareaService $tarea_service,
        private TagService $tag_service
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
            'success'  => true,
            'mensaje'  => 'Proyecto creado correctamente.',
            'proyecto' => $proyecto,
        ], 201);
    }

    public function show(Proyecto $proyecto): View
    {
        $tareas = $this->tarea_service->listarPorProyectoConSubtareasYTags($proyecto->id);

        $proyecto->loadCount([
            'tareas'                           => fn($q) => $q->whereNull('tarea_padre_id'),
            'tareas as tareas_terminadas_count' => fn($q) => $q->whereNull('tarea_padre_id')->where('estado', 'terminada'),
        ]);

        $progreso       = $this->proyecto_service->calcularProgreso($proyecto);
        $horas_totales  = $this->proyecto_service->calcularHorasTotales($proyecto);
        $todos_los_tags = $this->tag_service->listar();

        return view('proyectos.show', compact('proyecto', 'tareas', 'progreso', 'horas_totales', 'todos_los_tags'));
    }

    public function update(ActualizarProyectoRequest $request, Proyecto $proyecto): JsonResponse
    {
        $proyecto = $this->proyecto_service->actualizar($proyecto, $request->validated());
        $proyecto->loadCount(['tareas', 'tareas as tareas_terminadas_count' => fn($q) => $q->where('estado', 'terminada')]);
        $proyecto->progreso = $this->proyecto_service->calcularProgreso($proyecto);

        return response()->json([
            'success'  => true,
            'mensaje'  => 'Proyecto actualizado correctamente.',
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
