<?php

namespace App\Repositories\Contracts;

use App\Models\Proyecto;
use Illuminate\Database\Eloquent\Collection;

interface ProyectoRepositoryInterface
{
    public function obtenerTodos(): Collection;
    public function obtenerConEstadisticas(): Collection;
    public function buscarPorId(int $id): Proyecto;
    public function crear(array $datos): Proyecto;
    public function actualizar(Proyecto $proyecto, array $datos): Proyecto;
    public function eliminar(Proyecto $proyecto): void;
}
