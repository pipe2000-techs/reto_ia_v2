<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tarea extends Model
{
    protected $table = 'tareas';

    const PRIORIDADES = ['baja', 'media', 'alta'];
    const ESTADOS = ['backlog', 'en_progreso', 'testing', 'terminada'];
    const ESTADO_INICIAL = 'backlog';
    const ESTADO_FINAL = 'terminada';

    protected $fillable = [
        'proyecto_id',
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
}
