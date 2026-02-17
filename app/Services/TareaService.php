<?php

namespace App\Services;

use App\Models\Tarea;
use App\Repositories\Contracts\TareaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class TareaService
{
    public function __construct(
        private TareaRepositoryInterface $tarea_repository
    ) {}

    public function listarPorProyecto(int $proyecto_id): Collection
    {
        return $this->tarea_repository->obtenerPorProyecto($proyecto_id);
    }

    public function guardar(array $datos): Tarea
    {
        return $this->tarea_repository->crear($datos);
    }

    public function actualizar(Tarea $tarea, array $datos): Tarea
    {
        if (isset($datos['estado'])) {
            $this->validarTransicionEstado($tarea->estado, $datos['estado']);
        }

        return $this->tarea_repository->actualizar($tarea, $datos);
    }

    public function eliminar(Tarea $tarea): void
    {
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
