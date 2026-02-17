<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\Tarea;
use App\Repositories\Contracts\TagRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class TagService
{
    public function __construct(
        private TagRepositoryInterface $tag_repository
    ) {}

    public function listar(): Collection
    {
        return $this->tag_repository->obtenerTodos();
    }

    public function guardar(array $datos): Tag
    {
        if ($this->tag_repository->existeNombre($datos['nombre'])) {
            throw ValidationException::withMessages([
                'nombre' => 'Ya existe un tag con ese nombre.',
            ]);
        }

        return $this->tag_repository->crear($datos);
    }

    public function eliminar(Tag $tag): void
    {
        $this->tag_repository->eliminar($tag);
    }

    public function sincronizarTagsTarea(Tarea $tarea, array $tag_ids): void
    {
        $tarea->tags()->sync($tag_ids);
    }
}
