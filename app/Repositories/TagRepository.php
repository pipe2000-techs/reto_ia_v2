<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Repositories\Contracts\TagRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TagRepository implements TagRepositoryInterface
{
    public function obtenerTodos(): Collection
    {
        return Tag::orderBy('nombre')->get();
    }

    public function buscarPorId(int $id): Tag
    {
        return Tag::findOrFail($id);
    }

    public function crear(array $datos): Tag
    {
        return Tag::create($datos);
    }

    public function eliminar(Tag $tag): void
    {
        $tag->delete();
    }

    public function existeNombre(string $nombre): bool
    {
        return Tag::where('nombre', $nombre)->exists();
    }
}
