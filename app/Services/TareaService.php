<?php

namespace App\Services;

use App\Models\Tarea;
use App\Repositories\Contracts\TareaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class TareaService
{
    public function __construct(
        private TareaRepositoryInterface $tarea_repository,
        private TagService $tag_service
    ) {}

    public function listarPorProyecto(int $proyecto_id): Collection
    {
        return $this->tarea_repository->obtenerPorProyecto($proyecto_id);
    }

    public function listarPorProyectoConSubtareasYTags(int $proyecto_id): Collection
    {
        return $this->tarea_repository->obtenerPorProyectoConSubtareasYTags($proyecto_id);
    }

    public function guardar(array $datos): Tarea
    {
        $tag_ids = $datos['tags'] ?? [];
        unset($datos['tags']);

        $tarea = $this->tarea_repository->crear($datos);

        if (!empty($tag_ids)) {
            $this->tag_service->sincronizarTagsTarea($tarea, $tag_ids);
        }

        return $tarea->load('tags');
    }

    public function actualizar(Tarea $tarea, array $datos): Tarea
    {
        if (isset($datos['estado'])) {
            $this->validarTransicionEstado($tarea->estado, $datos['estado']);
        }

        $tag_ids = array_key_exists('tags', $datos) ? $datos['tags'] : null;
        unset($datos['tags']);

        $tarea = $this->tarea_repository->actualizar($tarea, $datos);

        if ($tag_ids !== null) {
            $this->tag_service->sincronizarTagsTarea($tarea, $tag_ids);
        }

        return $tarea->load('tags');
    }

    public function eliminar(Tarea $tarea): void
    {
        // Eliminar subtareas explícitamente antes de eliminar la tarea padre
        foreach ($tarea->subtareas as $subtarea) {
            $this->tarea_repository->eliminar($subtarea);
        }

        $this->tarea_repository->eliminar($tarea);
    }

    public function validarTransicionEstado(string $estado_actual, string $estado_nuevo): void
    {
        $transiciones_validas = [
            'backlog'     => ['en_progreso'],
            'en_progreso' => ['backlog', 'testing'],
            'testing'     => ['en_progreso', 'terminada'],
            'terminada'   => ['testing'],
        ];

        $permitidos = $transiciones_validas[$estado_actual] ?? [];

        if (!in_array($estado_nuevo, $permitidos)) {
            throw ValidationException::withMessages([
                'estado' => "No se puede cambiar de '{$estado_actual}' a '{$estado_nuevo}'.",
            ]);
        }
    }
}
