<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarea_tag', function (Blueprint $table) {
            $table->foreignId('tarea_id')->constrained('tareas')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->unique(['tarea_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarea_tag');
    }
};
