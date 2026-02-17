<?php

namespace App\Repositories;

use App\Models\Tarea;
use App\Repositories\Contracts\TareaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TareaRepository implements TareaRepositoryInterface
{
    public function obtenerPorProyecto(int $proyecto_id): Collection
    {
        return Tarea::where('proyecto_id', $proyecto_id)
            ->orderByRaw("FIELD(prioridad, 'alta', 'media', 'baja')")
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function buscarPorId(int $id): Tarea
    {
        return Tarea::findOrFail($id);
    }

    public function crear(array $datos): Tarea
    {
        return Tarea::create($datos);
    }

    public function actualizar(Tarea $tarea, array $datos): Tarea
    {
        $tarea->update($datos);
        return $tarea->fresh();
    }

    public function eliminar(Tarea $tarea): void
    {
        $tarea->delete();
    }
}
