<?php

namespace App\Repositories\Contracts;

use App\Models\Tarea;
use Illuminate\Database\Eloquent\Collection;

interface SubtareaRepositoryInterface
{
    public function obtenerPorTareaPadre(int $tarea_padre_id): Collection;
    public function crear(array $datos): Tarea;
    public function actualizar(Tarea $subtarea, array $datos): Tarea;
    public function eliminar(Tarea $subtarea): void;
    public function contarPorTareaPadre(int $tarea_padre_id): int;
}
