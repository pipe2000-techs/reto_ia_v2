@extends('layouts.app')

@section('title', $proyecto->nombre)

@section('content')
<!-- Header del proyecto -->
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Proyectos</a></li>
                <li class="breadcrumb-item active">{{ $proyecto->nombre }}</li>
            </ol>
        </nav>
        <h1 class="h3 mb-1">{{ $proyecto->nombre }}</h1>
        @if($proyecto->descripcion)
            <p class="text-muted mb-0">{{ $proyecto->descripcion }}</p>
        @endif
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_tarea">
        <i class="bi bi-plus-lg me-1"></i>Nueva Tarea
    </button>
</div>

<!-- Estadísticas -->
<div class="row g-3 mb-4">
    <div class="col-sm-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="h4 mb-0 fw-bold" id="stat_total">{{ count($tareas) }}</div>
                <small class="text-muted">Total tareas</small>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="h4 mb-0 fw-bold text-success" id="stat_terminadas">{{ $tareas->where('estado','terminada')->count() }}</div>
                <small class="text-muted">Terminadas</small>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="h4 mb-0 fw-bold text-primary" id="stat_progreso">{{ $progreso }}%</div>
                <small class="text-muted">Progreso</small>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="h4 mb-0 fw-bold text-info" id="stat_horas">{{ number_format($horas_totales, 1) }}h</div>
                <small class="text-muted">Horas estimadas</small>
            </div>
        </div>
    </div>
</div>

<!-- Barra de progreso global -->
<div class="card mb-4">
    <div class="card-body py-2">
        <div class="d-flex justify-content-between small mb-1">
            <span>Progreso del proyecto</span>
            <span id="lbl_progreso">{{ $progreso }}%</span>
        </div>
        <div class="progress">
            <div class="progress-bar bg-success" id="barra_progreso"
                role="progressbar"
                style="width: {{ $progreso }}%"
                aria-valuenow="{{ $progreso }}" aria-valuemin="0" aria-valuemax="100">
            </div>
        </div>
    </div>
</div>

<!-- Tabla de tareas -->
<div class="card">
    <div class="card-header bg-white fw-semibold">
        <i class="bi bi-list-check me-2"></i>Tareas
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="tabla_tareas">
                <thead class="table-light">
                    <tr>
                        <th>Título</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Horas est.</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody id="cuerpo_tareas">
                    @forelse($tareas as $tarea)
                        @include('proyectos._fila_tarea', ['tarea' => $tarea])
                    @empty
                        <tr id="fila_vacia">
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-2"></i>Sin tareas. ¡Crea la primera!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Crear/Editar Tarea -->
<div class="modal fade" id="modal_tarea" tabindex="-1" aria-labelledby="modal_tarea_titulo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_tarea_titulo">Nueva Tarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="form_tarea" novalidate>
                <div class="modal-body">
                    <input type="hidden" id="tarea_id" value="">

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="titulo" name="titulo" maxlength="150" required>
                        <div class="invalid-feedback" id="error_titulo"></div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion_tarea" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion_tarea" name="descripcion" rows="2"></textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="prioridad" class="form-label">Prioridad <span class="text-danger">*</span></label>
                            <select class="form-select" id="prioridad" name="prioridad" required>
                                <option value="baja">Baja</option>
                                <option value="media" selected>Media</option>
                                <option value="alta">Alta</option>
                            </select>
                            <div class="invalid-feedback" id="error_prioridad"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="estado_tarea" class="form-label">Estado</label>
                            <select class="form-select" id="estado_tarea" name="estado">
                                <option value="backlog">Backlog</option>
                                <option value="en_progreso">En Progreso</option>
                                <option value="testing">Testing</option>
                                <option value="terminada">Terminada</option>
                            </select>
                            <div class="invalid-feedback" id="error_estado_tarea"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="horas_estimadas" class="form-label">Horas estimadas</label>
                            <input type="number" class="form-control" id="horas_estimadas" name="horas_estimadas" min="0" max="9999.99" step="0.5">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btn_guardar_tarea">
                        <i class="bi bi-save me-1"></i>Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const PROYECTO_ID = {{ $proyecto->id }};
const URL_PROYECTO_TAREAS = `/proyectos/${PROYECTO_ID}/tareas`;
const URL_TAREAS = '/tareas';

// ----- Helpers -----
function limpiarErroresTarea() {
    $('#form_tarea .form-control, #form_tarea .form-select').removeClass('is-invalid');
    $('#form_tarea .invalid-feedback').text('');
}

function badgePrioridad(p) {
    const clases = { alta: 'danger', media: 'warning text-dark', baja: 'success' };
    const labels = { alta: 'Alta', media: 'Media', baja: 'Baja' };
    return `<span class="badge bg-${clases[p] || 'secondary'}">${labels[p] || p}</span>`;
}

function badgeEstado(e) {
    const clases = {
        backlog: 'secondary',
        en_progreso: 'primary',
        testing: 'warning text-dark',
        terminada: 'success'
    };
    const labels = {
        backlog: 'Backlog',
        en_progreso: 'En Progreso',
        testing: 'Testing',
        terminada: 'Terminada'
    };
    return `<span class="badge bg-${clases[e] || 'secondary'}">${labels[e] || e}</span>`;
}

function selectEstado(tarea_id, estado_actual) {
    const opciones = ['backlog','en_progreso','testing','terminada'];
    const labels   = { backlog:'Backlog', en_progreso:'En Progreso', testing:'Testing', terminada:'Terminada' };
    let html = `<select class="form-select form-select-sm select-estado-inline" data-id="${tarea_id}" style="min-width:130px;">`;
    opciones.forEach(function(op) {
        html += `<option value="${op}"${op === estado_actual ? ' selected' : ''}>${labels[op]}</option>`;
    });
    html += '</select>';
    return html;
}

function construirFilaHtml(t) {
    return `
    <tr id="fila_tarea_${t.id}" data-id="${t.id}">
        <td>
            <strong>${escapeHtml(t.titulo)}</strong>
            ${t.descripcion ? `<br><small class="text-muted">${escapeHtml(t.descripcion)}</small>` : ''}
        </td>
        <td>${badgePrioridad(t.prioridad)}</td>
        <td>${selectEstado(t.id, t.estado)}</td>
        <td>${t.horas_estimadas ? t.horas_estimadas + 'h' : '-'}</td>
        <td class="text-end">
            <button class="btn btn-sm btn-outline-secondary btn-editar-tarea me-1"
                data-id="${t.id}"
                data-titulo="${escapeAttr(t.titulo)}"
                data-descripcion="${escapeAttr(t.descripcion)}"
                data-prioridad="${t.prioridad}"
                data-estado="${t.estado}"
                data-horas="${t.horas_estimadas ?? ''}">
                <i class="bi bi-pencil"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger btn-eliminar-tarea"
                data-id="${t.id}"
                data-titulo="${escapeAttr(t.titulo)}">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>`;
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function escapeAttr(str) {
    if (!str) return '';
    return String(str).replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}

function actualizarEstadisticas() {
    const filas = $('#cuerpo_tareas tr[id^="fila_tarea_"]');
    const total = filas.length;
    let terminadas = 0;
    let horas = 0;

    filas.each(function() {
        const estado = $(this).find('.select-estado-inline').val();
        if (estado === 'terminada') terminadas++;
        const texto_horas = $(this).find('td:eq(3)').text().replace('h','').trim();
        if (texto_horas && texto_horas !== '-') horas += parseFloat(texto_horas) || 0;
    });

    const progreso = total > 0 ? Math.round((terminadas / total) * 100) : 0;

    $('#stat_total').text(total);
    $('#stat_terminadas').text(terminadas);
    $('#stat_progreso').text(progreso + '%');
    $('#stat_horas').text(horas.toFixed(1) + 'h');
    $('#barra_progreso').css('width', progreso + '%').attr('aria-valuenow', progreso);
    $('#lbl_progreso').text(progreso + '%');
}

let editando_tarea = false;

// ----- Abrir modal para crear tarea -----
$('#modal_tarea').on('show.bs.modal', function() {
    if (!editando_tarea) {
        $('#modal_tarea_titulo').text('Nueva Tarea');
        $('#tarea_id').val('');
        $('#form_tarea')[0].reset();
        limpiarErroresTarea();
    }
    editando_tarea = false;
});

// ----- Abrir modal para editar tarea -----
$(document).on('click', '.btn-editar-tarea', function() {
    editando_tarea = true;
    const $btn = $(this);
    $('#modal_tarea_titulo').text('Editar Tarea');
    $('#tarea_id').val($btn.data('id'));
    $('#titulo').val($btn.data('titulo'));
    $('#descripcion_tarea').val($btn.data('descripcion') || '');
    $('#prioridad').val($btn.data('prioridad'));
    $('#estado_tarea').val($btn.data('estado'));
    $('#horas_estimadas').val($btn.data('horas') || '');
    limpiarErroresTarea();
    $('#modal_tarea').modal('show');
});

// ----- Guardar tarea -----
$('#form_tarea').on('submit', function(e) {
    e.preventDefault();
    limpiarErroresTarea();

    const $btn = $('#btn_guardar_tarea');
    const id = $('#tarea_id').val();
    const es_edicion = id !== '';
    const url = es_edicion ? `${URL_TAREAS}/${id}` : URL_PROYECTO_TAREAS;
    const method = es_edicion ? 'PUT' : 'POST';

    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Guardando...');

    $.ajax({
        url: url,
        method: method,
        data: $(this).serialize() + (es_edicion ? '&_method=PUT' : ''),
        success: function(resp) {
            $('#modal_tarea').modal('hide');

            if (es_edicion) {
                $(`#fila_tarea_${resp.tarea.id}`).replaceWith(construirFilaHtml(resp.tarea));
            } else {
                $('#fila_vacia').remove();
                $('#cuerpo_tareas').prepend(construirFilaHtml(resp.tarea));
            }

            actualizarEstadisticas();
            Swal.fire({ icon: 'success', title: resp.mensaje, timer: 1500, showConfirmButton: false });
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                if (errors) {
                    $.each(errors, function(campo, mensajes) {
                        const campo_id = campo === 'estado' ? 'estado_tarea' : campo;
                        $(`#${campo_id}`).addClass('is-invalid');
                        $(`#error_${campo_id}`).text(mensajes[0]);
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error de validación', text: xhr.responseJSON.mensaje || 'Error.' });
                }
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un error inesperado.' });
            }
        },
        complete: function() {
            $btn.prop('disabled', false).html('<i class="bi bi-save me-1"></i>Guardar');
        }
    });
});

// ----- Cambio de estado inline -----
$(document).on('change', '.select-estado-inline', function() {
    const $select = $(this);
    const id = $select.data('id');
    const estado_nuevo = $select.val();

    $.ajax({
        url: `${URL_TAREAS}/${id}`,
        method: 'PUT',
        data: { _method: 'PUT', estado: estado_nuevo },
        success: function(resp) {
            actualizarEstadisticas();
        },
        error: function(xhr) {
            Swal.fire({ icon: 'error', title: 'Transición no permitida', text: xhr.responseJSON?.mensaje || 'Error al cambiar estado.' });
            // Revertir visualmente — recargar la fila requeriría otra petición; simplemente recargamos
            location.reload();
        }
    });
});

// ----- Eliminar tarea -----
$(document).on('click', '.btn-eliminar-tarea', function() {
    const id = $(this).data('id');
    const titulo = $(this).data('titulo');

    Swal.fire({
        title: '¿Eliminar tarea?',
        text: `Se eliminará "${titulo}".`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Sí, eliminar',
    }).then(function(result) {
        if (result.isConfirmed) {
            $.ajax({
                url: `${URL_TAREAS}/${id}`,
                method: 'DELETE',
                success: function(resp) {
                    $(`#fila_tarea_${id}`).fadeOut(300, function() {
                        $(this).remove();
                        if ($('#cuerpo_tareas tr[id^="fila_tarea_"]').length === 0) {
                            $('#cuerpo_tareas').html(`
                                <tr id="fila_vacia">
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox me-2"></i>Sin tareas. ¡Crea la primera!
                                    </td>
                                </tr>`);
                        }
                        actualizarEstadisticas();
                    });
                    Swal.fire({ icon: 'success', title: resp.mensaje, timer: 1500, showConfirmButton: false });
                },
                error: function() {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo eliminar la tarea.' });
                }
            });
        }
    });
});
</script>
@endsection
