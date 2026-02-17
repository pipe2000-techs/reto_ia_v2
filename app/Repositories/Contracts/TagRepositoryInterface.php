<?php

namespace App\Repositories\Contracts;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;

interface TagRepositoryInterface
{
    public function obtenerTodos(): Collection;
    public function buscarPorId(int $id): Tag;
    public function crear(array $datos): Tag;
    public function eliminar(Tag $tag): void;
    public function existeNombre(string $nombre): bool;
}
