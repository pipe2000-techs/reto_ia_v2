<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proyecto extends Model
{
    protected $table = 'proyectos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_limite',
    ];

    protected $casts = [
        'fecha_limite' => 'date',
    ];

    public function tareas(): HasMany
    {
        return $this->hasMany(Tarea::class, 'proyecto_id');
    }
}
