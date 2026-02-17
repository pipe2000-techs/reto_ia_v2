<?php

namespace App\Services;

use App\Models\Tarea;
use App\Repositories\Contracts\SubtareaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class SubtareaService
{
    public function __construct(
        private SubtareaRepositoryInterface $subtarea_repository,
        private TareaService $tarea_service,
        private TagService $tag_service
    ) {}

    public function guardar(array $datos, Tarea $tarea_padre): Tarea
    {
        if ($tarea_padre->tarea_padre_id !== null) {
            throw ValidationException::withMessages([
                'tarea_padre_id' => 'No se pueden crear subtareas de una subtarea. Máximo 2 niveles.',
            ]);
        }

        $tag_ids = $datos['tags'] ?? [];
        unset($datos['tags']);

        $datos['tarea_padre_id'] = $tarea_padre->id;
        $datos['proyecto_id']    = $tarea_padre->proyecto_id;

        $subtarea = $this->subtarea_repository->crear($datos);

        if (!empty($tag_ids)) {
            $this->tag_service->sincronizarTagsTarea($subtarea, $tag_ids);
        }

        return $subtarea->load('tags');
    }

    public function listarPorTareaPadre(int $tarea_padre_id): Collection
    {
        return $this->subtarea_repository->obtenerPorTareaPadre($tarea_padre_id);
    }

    public function actualizar(Tarea $subtarea, array $datos): Tarea
    {
        if (isset($datos['estado'])) {
            $this->tarea_service->validarTransicionEstado($subtarea->estado, $datos['estado']);
        }

        $tag_ids = $datos['tags'] ?? null;
        unset($datos['tags']);

        $subtarea = $this->subtarea_repository->actualizar($subtarea, $datos);

        if ($tag_ids !== null) {
            $this->tag_service->sincronizarTagsTarea($subtarea, $tag_ids);
        }

        return $subtarea->load('tags');
    }

    public function eliminar(Tarea $subtarea): void
    {
        $this->subtarea_repository->eliminar($subtarea);
    }

    public function contarSubtareas(int $tarea_padre_id): int
    {
        return $this->subtarea_repository->contarPorTareaPadre($tarea_padre_id);
    }
}
