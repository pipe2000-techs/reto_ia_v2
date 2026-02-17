<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = [
        'nombre',
        'color',
    ];

    public function tareas(): BelongsToMany
    {
        return $this->belongsToMany(Tarea::class, 'tarea_tag');
    }
}
