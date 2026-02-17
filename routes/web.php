<?php

use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\TareaController;
use Illuminate\Support\Facades\Route;

// Dashboard = lista de proyectos
Route::get('/', [ProyectoController::class, 'index']);

// Proyectos CRUD (sin create y edit porque son modales)
Route::resource('proyectos', ProyectoController::class)->except(['create', 'edit']);

// Tareas (anidadas bajo proyecto para store, independientes para update/destroy)
Route::post('proyectos/{proyecto}/tareas', [TareaController::class, 'store']);
Route::put('tareas/{tarea}', [TareaController::class, 'update']);
Route::delete('tareas/{tarea}', [TareaController::class, 'destroy']);
