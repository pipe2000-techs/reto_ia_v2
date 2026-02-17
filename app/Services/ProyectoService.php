<?php

namespace App\Services;

use App\Models\Proyecto;
use App\Repositories\Contracts\ProyectoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProyectoService
{
    public function __construct(
        private ProyectoRepositoryInterface $proyecto_repository
    ) {}

    public function listar(): Collection
    {
        return $this->proyecto_repository->obtenerConEstadisticas();
    }

    public function guardar(array $datos): Proyecto
    {
        return $this->proyecto_repository->crear($datos);
    }

    public function actualizar(Proyecto $proyecto, array $datos): Proyecto
    {
        return $this->proyecto_repository->actualizar($proyecto, $datos);
    }

    public function eliminar(Proyecto $proyecto): void
    {
        $this->proyecto_repository->eliminar($proyecto);
    }

    public function calcularProgreso(Proyecto $proyecto): int
    {
        $total = $proyecto->tareas_count ?? $proyecto->tareas()->count();

        if ($total === 0) {
            return 0;
        }

        $terminadas = $proyecto->tareas_terminadas_count ?? $proyecto->tareas()->where('estado', 'terminada')->count();

        return (int) round(($terminadas / $total) * 100);
    }

    public function calcularHorasTotales(Proyecto $proyecto): float
    {
        return (float) $proyecto->tareas()->sum('horas_estimadas');
    }
}
