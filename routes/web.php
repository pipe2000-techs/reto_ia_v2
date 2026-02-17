<?php

use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\SubtareaController;
use App\Http\Controllers\TagController;
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

// Subtareas (anidadas bajo la tarea padre para store, independientes para update/destroy)
Route::post('tareas/{tarea}/subtareas', [SubtareaController::class, 'store']);
Route::put('subtareas/{tarea}', [SubtareaController::class, 'update']);
Route::delete('subtareas/{tarea}', [SubtareaController::class, 'destroy']);

// Tags globales
Route::get('tags', [TagController::class, 'index']);
Route::post('tags', [TagController::class, 'store']);
Route::delete('tags/{tag}', [TagController::class, 'destroy']);
