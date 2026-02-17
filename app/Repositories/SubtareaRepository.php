<?php

namespace App\Repositories;

use App\Models\Tarea;
use App\Repositories\Contracts\SubtareaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SubtareaRepository implements SubtareaRepositoryInterface
{
    public function obtenerPorTareaPadre(int $tarea_padre_id): Collection
    {
        return Tarea::where('tarea_padre_id', $tarea_padre_id)
            ->with('tags')
            ->orderByRaw("FIELD(prioridad, 'alta', 'media', 'baja')")
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function crear(array $datos): Tarea
    {
        return Tarea::create($datos);
    }

    public function actualizar(Tarea $subtarea, array $datos): Tarea
    {
        $subtarea->update($datos);
        return $subtarea->fresh();
    }

    public function eliminar(Tarea $subtarea): void
    {
        $subtarea->delete();
    }

    public function contarPorTareaPadre(int $tarea_padre_id): int
    {
        return Tarea::where('tarea_padre_id', $tarea_padre_id)->count();
    }
}
