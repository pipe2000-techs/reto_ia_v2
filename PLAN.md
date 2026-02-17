# Plan: Mini Gestor de Proyectos

## Context
Crear una aplicación web desde cero en `c:\laragon\www\claude` usando Laravel 12 + Bootstrap 5 + jQuery.
Arquitectura Controller → Service → Repository. Sin autenticación. Lo más simple posible.

---

## Funcionalidades
- **Proyectos**: CRUD con nombre, descripción, fecha límite
- **Tareas**: CRUD dentro de cada proyecto con título, descripción, prioridad (alta/media/baja), estado (backlog/en_progreso/testing/terminada), estimación de horas

## Convenciones de código
- **Funciones y métodos**: en español → `obtenerTodos()`, `crearProyecto()`, `calcularProgreso()`
- **Variables**: snake_case en español → `$nombre_proyecto`, `$horas_estimadas`, `$fecha_limite`
- **Constantes**: MAYÚSCULAS → `PRIORIDADES`, `ESTADOS_TAREA`, `HORAS_MAXIMAS`
- **Clases y modelos**: PascalCase en español → `Proyecto`, `Tarea`
- **Tablas BD**: snake_case en español → `proyectos`, `tareas`
- **Columnas BD**: snake_case en español → `nombre`, `fecha_limite`, `horas_estimadas`

---

## Stack
| Capa | Tecnología |
|------|-----------|
| Backend | Laravel 12 |
| Frontend | Bootstrap 5 + jQuery |
| DB | MySQL (Laragon) |
| UI extras | SweetAlert2 (confirmaciones) |

---

## Estructura de Archivos

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── ProyectoController.php
│   │   └── TareaController.php
│   └── Requests/
│       ├── GuardarProyectoRequest.php
│       ├── ActualizarProyectoRequest.php
│       ├── GuardarTareaRequest.php
│       └── ActualizarTareaRequest.php
├── Models/
│   ├── Proyecto.php
│   └── Tarea.php
├── Repositories/
│   ├── Contracts/
│   │   ├── ProyectoRepositoryInterface.php
│   │   └── TareaRepositoryInterface.php
│   ├── ProyectoRepository.php
│   └── TareaRepository.php
└── Services/
    ├── ProyectoService.php
    └── TareaService.php

database/migrations/
├── xxxx_create_proyectos_table.php
└── xxxx_create_tareas_table.php

resources/views/
├── layouts/
│   └── app.blade.php          # layout principal con navbar
├── proyectos/
│   ├── index.blade.php        # lista de proyectos + modal crear/editar
│   └── show.blade.php         # detalle proyecto + lista tareas + modal crear/editar tarea

routes/
└── web.php
```

---

## Base de Datos

### proyectos
| Campo | Tipo | Detalles |
|-------|------|---------|
| id | bigint PK | auto |
| nombre | varchar(150) | not null |
| descripcion | text | nullable |
| fecha_limite | date | nullable |
| timestamps | | |

### tareas
| Campo | Tipo | Detalles |
|-------|------|---------|
| id | bigint PK | auto |
| proyecto_id | FK → proyectos | cascade delete |
| titulo | varchar(150) | not null |
| descripcion | text | nullable |
| prioridad | enum | baja, media, alta |
| estado | enum | backlog, en_progreso, testing, terminada |
| horas_estimadas | decimal(6,2) | nullable |
| timestamps | | |

---

## Arquitectura por Capa

### Repositories (solo consultas Eloquent)
- `ProyectoRepository`: obtenerTodos(), buscarPorId(), crear(), actualizar(), eliminar(), obtenerConEstadisticas()
- `TareaRepository`: obtenerPorProyecto(), buscarPorId(), crear(), actualizar(), eliminar()

### Services (lógica de negocio)
- `ProyectoService`: calcularProgreso(), calcularHorasTotales(), listar(), guardar(), actualizar(), eliminar()
- `TareaService`: validarTransicionEstado(), listarPorProyecto(), guardar(), actualizar(), eliminar()

### Controllers (solo request/response)
- `ProyectoController`: index, store, show, update, destroy → JSON o Blade según Accept header
- `TareaController`: store, update, destroy → JSON (AJAX desde show.blade.php)

### Constantes en modelos
```php
// Tarea.php
const PRIORIDADES = ['baja', 'media', 'alta'];
const ESTADOS = ['backlog', 'en_progreso', 'testing', 'terminada'];
const ESTADO_INICIAL = 'backlog';
const ESTADO_FINAL = 'terminada';
```

---

## Rutas (web.php)

```php
// Dashboard = lista proyectos
Route::get('/', [ProyectoController::class, 'index']);

// Proyectos
Route::resource('proyectos', ProyectoController::class)->except(['create','edit']);

// Tareas (anidadas bajo proyecto)
Route::post('proyectos/{proyecto}/tareas', [TareaController::class, 'store']);
Route::put('tareas/{tarea}', [TareaController::class, 'update']);
Route::delete('tareas/{tarea}', [TareaController::class, 'destroy']);
```

---

## Vistas principales

### `proyectos/index.blade.php`
- Cards de proyectos con nombre, fecha límite, barra de progreso, conteo de tareas
- Botón "Nuevo Proyecto" → abre modal Bootstrap
- jQuery AJAX: POST crear, PUT editar, DELETE eliminar → reload de cards

### `proyectos/show.blade.php`
- Header con info del proyecto
- Tabla de tareas con badges de prioridad y estado
- Botón "Nueva Tarea" → abre modal
- jQuery AJAX para CRUD de tareas + cambio de estado inline (select en la tabla)

---

## Patrón jQuery/AJAX

```js
// Ejemplo crear proyecto
$('#form_proyecto').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: '/proyectos',
        method: 'POST',
        data: $(this).serialize(),
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(respuesta) { /* reload cards */ },
        error: function(error) { /* mostrar errores */ }
    });
});
```

---

## Comandos de instalación

```bash
# 1. Crear proyecto Laravel 12
composer create-project laravel/laravel . "^12.0"

# 2. Configurar .env (DB MySQL Laragon)
# DB_DATABASE=claude, DB_USERNAME=root, DB_PASSWORD=

# 3. Ejecutar migraciones
php artisan migrate

# 4. Iniciar servidor (Laragon ya lo sirve)
# Acceder en: http://localhost/claude/public
```

---

## Binding en AppServiceProvider

```php
$this->app->bind(ProyectoRepositoryInterface::class, ProyectoRepository::class);
$this->app->bind(TareaRepositoryInterface::class, TareaRepository::class);
```

---

## Verificación final
1. Abrir `http://localhost/claude/public` → ver dashboard vacío
2. Crear un proyecto → aparece card con 0% progreso
3. Abrir proyecto → crear tareas con distintas prioridades y estados
4. Mover tarea a "terminada" → progreso del proyecto sube
5. Eliminar tarea/proyecto → confirmación con SweetAlert2
6. Todo sin recargar la página (AJAX)
