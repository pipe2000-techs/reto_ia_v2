<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Tag;

class Tarea extends Model
{
    protected $table = 'tareas';

    const PRIORIDADES = ['baja', 'media', 'alta'];
    const ESTADOS = ['backlog', 'en_progreso', 'testing', 'terminada'];
    const ESTADO_INICIAL = 'backlog';
    const ESTADO_FINAL = 'terminada';

    protected $fillable = [
        'proyecto_id',
        'tarea_padre_id',
        'titulo',
        'descripcion',
        'prioridad',
        'estado',
        'horas_estimadas',
    ];

    protected $casts = [
        'horas_estimadas' => 'decimal:2',
    ];

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function padre(): BelongsTo
    {
        return $this->belongsTo(Tarea::class, 'tarea_padre_id');
    }

    public function subtareas(): HasMany
    {
        return $this->hasMany(Tarea::class, 'tarea_padre_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'tarea_tag');
    }
}
