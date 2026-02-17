<?php

namespace App\Repositories;

use App\Models\Proyecto;
use App\Repositories\Contracts\ProyectoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProyectoRepository implements ProyectoRepositoryInterface
{
    public function obtenerTodos(): Collection
    {
        return Proyecto::orderBy('created_at', 'desc')->get();
    }

    public function obtenerConEstadisticas(): Collection
    {
        return Proyecto::withCount('tareas')
            ->withCount(['tareas as tareas_terminadas_count' => function ($query) {
                $query->where('estado', 'terminada');
            }])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function buscarPorId(int $id): Proyecto
    {
        return Proyecto::findOrFail($id);
    }

    public function crear(array $datos): Proyecto
    {
        return Proyecto::create($datos);
    }

    public function actualizar(Proyecto $proyecto, array $datos): Proyecto
    {
        $proyecto->update($datos);
        return $proyecto->fresh();
    }

    public function eliminar(Proyecto $proyecto): void
    {
        $proyecto->delete();
    }
}
