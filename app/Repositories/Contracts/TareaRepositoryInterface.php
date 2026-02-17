<?php

namespace App\Repositories\Contracts;

use App\Models\Tarea;
use Illuminate\Database\Eloquent\Collection;

interface TareaRepositoryInterface
{
    public function obtenerPorProyecto(int $proyecto_id): Collection;
    public function buscarPorId(int $id): Tarea;
    public function crear(array $datos): Tarea;
    public function actualizar(Tarea $tarea, array $datos): Tarea;
    public function eliminar(Tarea $tarea): void;
}
