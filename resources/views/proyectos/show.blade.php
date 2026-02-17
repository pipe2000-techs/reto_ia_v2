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
    <div class="d-flex gap-2 flex-wrap">
        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal_gestionar_tags">
            <i class="bi bi-tags me-1"></i>Tags
        </button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_tarea">
            <i class="bi bi-plus-lg me-1"></i>Nueva Tarea
        </button>
    </div>
</div>

<!-- Estadísticas -->
<div class="row g-3 mb-4">
    <div class="col-sm-3">
        <div class="card text-center">
            <div class="card-body py-3">
                <div class="h4 mb-0 fw-bold" id="stat_total">{{ $tareas->count() }}</div>
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
                        <th>Tags</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody id="cuerpo_tareas">
                    @forelse($tareas as $tarea)
                        @include('proyectos._fila_tarea', ['tarea' => $tarea])
                    @empty
                        <tr id="fila_vacia">
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-2"></i>Sin tareas. ¡Crea la primera!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ===== Modal Crear/Editar Tarea ===== -->
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

                    <!-- Tags -->
                    <div class="mb-0 mt-3">
                        <label class="form-label">Tags</label>
                        <div id="contenedor_tags_tarea" class="d-flex flex-wrap gap-2 mb-2 p-2 border rounded" style="min-height:38px;"></div>
                        <div class="d-flex gap-2 align-items-center mt-1">
                            <input type="text" class="form-control form-control-sm" id="nuevo_tag_nombre_tarea"
                                   placeholder="Nuevo tag..." maxlength="50" style="max-width:160px;">
                            <input type="color" class="form-control form-control-color form-control-sm"
                                   id="nuevo_tag_color_tarea" value="#0d6efd" title="Color del tag" style="width:45px;height:31px;">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btn_crear_tag_inline_tarea">
                                <i class="bi bi-plus-lg"></i> Crear
                            </button>
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

<!-- ===== Modal Crear/Editar Subtarea ===== -->
<div class="modal fade" id="modal_subtarea" tabindex="-1" aria-labelledby="modal_subtarea_titulo" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_subtarea_titulo">Nueva Subtarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="form_subtarea" novalidate>
                <div class="modal-body">
                    <input type="hidden" id="subtarea_id" value="">
                    <input type="hidden" id="subtarea_padre_id" value="">

                    <div class="mb-3">
                        <label for="subtarea_titulo" class="form-label">Título <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="subtarea_titulo" name="titulo" maxlength="150" required>
                        <div class="invalid-feedback" id="error_subtarea_titulo"></div>
                    </div>

                    <div class="mb-3">
                        <label for="subtarea_descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="subtarea_descripcion" name="descripcion" rows="2"></textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="subtarea_prioridad" class="form-label">Prioridad <span class="text-danger">*</span></label>
                            <select class="form-select" id="subtarea_prioridad" name="prioridad" required>
                                <option value="baja">Baja</option>
                                <option value="media" selected>Media</option>
                                <option value="alta">Alta</option>
                            </select>
                            <div class="invalid-feedback" id="error_subtarea_prioridad"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="subtarea_estado" class="form-label">Estado</label>
                            <select class="form-select" id="subtarea_estado" name="estado">
                                <option value="backlog">Backlog</option>
                                <option value="en_progreso">En Progreso</option>
                                <option value="testing">Testing</option>
                                <option value="terminada">Terminada</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="subtarea_horas" class="form-label">Horas estimadas</label>
                            <input type="number" class="form-control" id="subtarea_horas" name="horas_estimadas" min="0" max="9999.99" step="0.5">
                        </div>
                    </div>

                    <!-- Tags -->
                    <div class="mb-0 mt-3">
                        <label class="form-label">Tags</label>
                        <div id="contenedor_tags_subtarea" class="d-flex flex-wrap gap-2 mb-2 p-2 border rounded" style="min-height:38px;"></div>
                        <div class="d-flex gap-2 align-items-center mt-1">
                            <input type="text" class="form-control form-control-sm" id="nuevo_tag_nombre_subtarea"
                                   placeholder="Nuevo tag..." maxlength="50" style="max-width:160px;">
                            <input type="color" class="form-control form-control-color form-control-sm"
                                   id="nuevo_tag_color_subtarea" value="#0d6efd" title="Color del tag" style="width:45px;height:31px;">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btn_crear_tag_inline_subtarea">
                                <i class="bi bi-plus-lg"></i> Crear
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btn_guardar_subtarea">
                        <i class="bi bi-save me-1"></i>Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== Modal Gestionar Tags ===== -->
<div class="modal fade" id="modal_gestionar_tags" tabindex="-1" aria-labelledby="modal_tags_titulo" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_tags_titulo"><i class="bi bi-tags me-2"></i>Gestionar Tags</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div id="lista_tags_gestion" class="mb-3"></div>
                <hr>
                <p class="fw-semibold mb-2">Crear nuevo tag</p>
                <div class="d-flex gap-2 align-items-center">
                    <input type="text" class="form-control" id="nuevo_tag_nombre_gestion"
                           placeholder="Nombre del tag..." maxlength="50">
                    <input type="color" class="form-control form-control-color"
                           id="nuevo_tag_color_gestion" value="#0d6efd" title="Color" style="width:50px;height:38px;">
                    <button type="button" class="btn btn-primary text-nowrap" id="btn_crear_tag_gestion">
                        <i class="bi bi-plus-lg"></i> Crear
                    </button>
                </div>
                <div class="text-danger small mt-1" id="error_tag_gestion"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const PROYECTO_ID         = {{ $proyecto->id }};
const URL_PROYECTO_TAREAS = `/proyectos/${PROYECTO_ID}/tareas`;
const URL_TAREAS          = '/tareas';
const URL_SUBTAREAS       = '/subtareas';
const URL_TAGS            = '/tags';

let TODOS_LOS_TAGS = @json($todos_los_tags);

// ----- Helpers -----
function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function escapeAttr(str) {
    if (str === null || str === undefined) return '';
    return String(str).replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}
function badgePrioridad(p) {
    const clases = { alta: 'danger', media: 'warning text-dark', baja: 'success' };
    const labels = { alta: 'Alta', media: 'Media', baja: 'Baja' };
    return `<span class="badge bg-${clases[p] || 'secondary'}">${labels[p] || p}</span>`;
}
function selectEstado(id, estado_actual, clase_css) {
    const opciones = ['backlog','en_progreso','testing','terminada'];
    const labels   = { backlog:'Backlog', en_progreso:'En Progreso', testing:'Testing', terminada:'Terminada' };
    let html = `<select class="form-select form-select-sm ${clase_css}" data-id="${id}" style="min-width:130px;">`;
    opciones.forEach(op => { html += `<option value="${op}"${op === estado_actual ? ' selected' : ''}>${labels[op]}</option>`; });
    return html + '</select>';
}
function renderizarTagsBadges(tags) {
    if (!tags || tags.length === 0) return '';
    return tags.map(t => `<span class="badge me-1" style="background-color:${escapeHtml(t.color)}" data-tag-id="${t.id}">${escapeHtml(t.nombre)}</span>`).join('');
}

// ----- Poblar checkboxes de tags -----
function poblarCheckboxesTags(contenedor_id, tags_seleccionados_ids) {
    const $c = $(`#${contenedor_id}`);
    $c.empty();
    if (TODOS_LOS_TAGS.length === 0) {
        $c.html('<small class="text-muted">Sin tags disponibles. Crea uno arriba.</small>');
        return;
    }
    TODOS_LOS_TAGS.forEach(function(tag) {
        const checked = tags_seleccionados_ids.includes(tag.id) ? 'checked' : '';
        $c.append(`<div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="tags[]"
                   id="tag_check_${contenedor_id}_${tag.id}" value="${tag.id}" ${checked}>
            <label class="form-check-label" for="tag_check_${contenedor_id}_${tag.id}">
                <span class="badge" style="background-color:${escapeHtml(tag.color)}">${escapeHtml(tag.nombre)}</span>
            </label>
        </div>`);
    });
}
function recogerTagIds(contenedor_id) {
    const ids = [];
    $(`#${contenedor_id} input[type="checkbox"]:checked`).each(function() { ids.push($(this).val()); });
    return ids;
}

// ----- Actualizar estadísticas (solo filas raíz) -----
function actualizarEstadisticas() {
    const filas = $('#cuerpo_tareas tr[id^="fila_tarea_"]');
    const total = filas.length;
    let terminadas = 0, horas = 0;
    filas.each(function() {
        if ($(this).find('.select-estado-inline').val() === 'terminada') terminadas++;
        const txt = $(this).find('td:eq(3)').text().replace('h','').trim();
        if (txt && txt !== '-') horas += parseFloat(txt) || 0;
    });
    const progreso = total > 0 ? Math.round((terminadas / total) * 100) : 0;
    $('#stat_total').text(total);
    $('#stat_terminadas').text(terminadas);
    $('#stat_progreso').text(progreso + '%');
    $('#stat_horas').text(horas.toFixed(1) + 'h');
    $('#barra_progreso').css('width', progreso + '%').attr('aria-valuenow', progreso);
    $('#lbl_progreso').text(progreso + '%');
}

// ----- Construir fila tarea -----
function construirFilaHtml(t) {
    const sc  = t.subtareas_count ?? 0;
    const tog = sc > 0
        ? `<button class="btn btn-sm btn-link p-0 me-1 btn-toggle-subtareas" data-id="${t.id}" data-expandido="0" title="Ver subtareas"><i class="bi bi-chevron-right"></i></button>`
        : `<span class="d-inline-block me-1" style="width:22px;"></span>`;
    const bsc  = sc > 0 ? `<span class="badge bg-secondary rounded-pill badge-subtareas-${t.id}">${sc}</span>` : '';
    const tag_ids_str = (t.tags && t.tags.length > 0) ? t.tags.map(tg => tg.id).join(',') : '';

    return `<tr id="fila_tarea_${t.id}" data-id="${t.id}" data-subtareas="${sc}">
        <td>${tog}<strong>${escapeHtml(t.titulo)}</strong>
            <button class="btn btn-link btn-sm p-0 ms-1 btn-nueva-subtarea text-secondary"
                    data-tarea-id="${t.id}" data-tarea-titulo="${escapeAttr(t.titulo)}" title="Agregar subtarea">
                <i class="bi bi-node-plus"></i>${bsc}
            </button>
            ${t.descripcion ? `<br><small class="text-muted ps-4">${escapeHtml(t.descripcion)}</small>` : ''}
        </td>
        <td>${badgePrioridad(t.prioridad)}</td>
        <td>${selectEstado(t.id, t.estado, 'select-estado-inline')}</td>
        <td>${t.horas_estimadas ? t.horas_estimadas + 'h' : '-'}</td>
        <td>${renderizarTagsBadges(t.tags)}</td>
        <td class="text-end">
            <button class="btn btn-sm btn-outline-secondary btn-editar-tarea me-1"
                data-id="${t.id}" data-titulo="${escapeAttr(t.titulo)}"
                data-descripcion="${escapeAttr(t.descripcion)}" data-prioridad="${t.prioridad}"
                data-estado="${t.estado}" data-horas="${t.horas_estimadas ?? ''}"
                data-tags="${tag_ids_str}"><i class="bi bi-pencil"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger btn-eliminar-tarea"
                data-id="${t.id}" data-titulo="${escapeAttr(t.titulo)}"><i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>`;
}

// ----- Construir fila subtarea -----
function construirFilaSubtareaHtml(s) {
    const tag_ids_str = (s.tags && s.tags.length > 0) ? s.tags.map(tg => tg.id).join(',') : '';
    return `<tr id="fila_subtarea_${s.id}"
        class="fila-subtarea fila-subtarea-padre-${s.tarea_padre_id} table-light"
        data-id="${s.id}" data-padre-id="${s.tarea_padre_id}">
        <td class="ps-5"><i class="bi bi-arrow-return-right text-muted me-1"></i>
            <strong>${escapeHtml(s.titulo)}</strong>
            ${s.descripcion ? `<br><small class="text-muted ps-4">${escapeHtml(s.descripcion)}</small>` : ''}
        </td>
        <td>${badgePrioridad(s.prioridad)}</td>
        <td>${selectEstado(s.id, s.estado, 'select-estado-subtarea-inline')}</td>
        <td>${s.horas_estimadas ? s.horas_estimadas + 'h' : '-'}</td>
        <td>${renderizarTagsBadges(s.tags)}</td>
        <td class="text-end">
            <button class="btn btn-sm btn-outline-secondary btn-editar-subtarea me-1"
                data-id="${s.id}" data-padre-id="${s.tarea_padre_id}"
                data-titulo="${escapeAttr(s.titulo)}" data-descripcion="${escapeAttr(s.descripcion)}"
                data-prioridad="${s.prioridad}" data-estado="${s.estado}"
                data-horas="${s.horas_estimadas ?? ''}" data-tags="${tag_ids_str}">
                <i class="bi bi-pencil"></i>
            </button>
            <button class="btn btn-sm btn-outline-danger btn-eliminar-subtarea"
                data-id="${s.id}" data-titulo="${escapeAttr(s.titulo)}"
                data-padre-id="${s.tarea_padre_id}"><i class="bi bi-trash"></i>
            </button>
        </td>
    </tr>`;
}

// ==========================================
// TAREAS
// ==========================================
function limpiarErroresTarea() {
    $('#form_tarea .form-control, #form_tarea .form-select').removeClass('is-invalid');
    $('#form_tarea .invalid-feedback').text('');
}

let editando_tarea = false;

$('#modal_tarea').on('show.bs.modal', function() {
    if (!editando_tarea) {
        $('#modal_tarea_titulo').text('Nueva Tarea');
        $('#tarea_id').val('');
        $('#form_tarea')[0].reset();
        limpiarErroresTarea();
        poblarCheckboxesTags('contenedor_tags_tarea', []);
    }
    editando_tarea = false;
});

$(document).on('click', '.btn-editar-tarea', function() {
    editando_tarea = true;
    const $btn = $(this);
    const tags_str = $btn.data('tags') ? String($btn.data('tags')) : '';
    const tags_sel = tags_str ? tags_str.split(',').map(Number).filter(Boolean) : [];
    $('#modal_tarea_titulo').text('Editar Tarea');
    $('#tarea_id').val($btn.data('id'));
    $('#titulo').val($btn.data('titulo'));
    $('#descripcion_tarea').val($btn.data('descripcion') || '');
    $('#prioridad').val($btn.data('prioridad'));
    $('#estado_tarea').val($btn.data('estado'));
    $('#horas_estimadas').val($btn.data('horas') || '');
    limpiarErroresTarea();
    poblarCheckboxesTags('contenedor_tags_tarea', tags_sel);
    $('#modal_tarea').modal('show');
});

$('#form_tarea').on('submit', function(e) {
    e.preventDefault();
    limpiarErroresTarea();
    const $btn  = $('#btn_guardar_tarea');
    const id    = $('#tarea_id').val();
    const es_ed = id !== '';
    const url    = es_ed ? `${URL_TAREAS}/${id}` : URL_PROYECTO_TAREAS;

    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Guardando...');

    let data = $(this).serialize();
    if (es_ed) data += '&_method=PUT';
    const tag_ids = recogerTagIds('contenedor_tags_tarea');
    if (es_ed && tag_ids.length === 0) {
        data += '&tags=';
    } else {
        tag_ids.forEach(tid => { data += `&tags[]=${tid}`; });
    }

    $.ajax({
        url: url, method: 'POST', data: data,
        success: function(resp) {
            $('#modal_tarea').modal('hide');
            if (es_ed) {
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
                        const cid = campo === 'estado' ? 'estado_tarea' : campo;
                        $(`#${cid}`).addClass('is-invalid');
                        $(`#error_${cid}`).text(mensajes[0]);
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error de validación', text: xhr.responseJSON.mensaje || 'Error.' });
                }
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un error inesperado.' });
            }
        },
        complete: function() { $btn.prop('disabled', false).html('<i class="bi bi-save me-1"></i>Guardar'); }
    });
});

$(document).on('change', '.select-estado-inline', function() {
    const id = $(this).data('id');
    const estado_nuevo = $(this).val();
    $.ajax({
        url: `${URL_TAREAS}/${id}`, method: 'POST',
        data: { _method: 'PUT', estado: estado_nuevo },
        success: function() { actualizarEstadisticas(); },
        error: function(xhr) {
            Swal.fire({ icon: 'error', title: 'Transición no permitida', text: xhr.responseJSON?.mensaje || 'Error.' });
            location.reload();
        }
    });
});

$(document).on('click', '.btn-eliminar-tarea', function() {
    const id = $(this).data('id'), titulo = $(this).data('titulo');
    Swal.fire({
        title: '¿Eliminar tarea?',
        text: `Se eliminará "${titulo}" y todas sus subtareas.`,
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#dc3545', cancelButtonText: 'Cancelar', confirmButtonText: 'Sí, eliminar',
    }).then(function(result) {
        if (!result.isConfirmed) return;
        $.ajax({
            url: `${URL_TAREAS}/${id}`, method: 'POST', data: { _method: 'DELETE' },
            success: function(resp) {
                if (resp.subtarea_ids && resp.subtarea_ids.length > 0) {
                    resp.subtarea_ids.forEach(sid => $(`#fila_subtarea_${sid}`).remove());
                }
                $(`#fila_tarea_${id}`).fadeOut(300, function() {
                    $(this).remove();
                    if ($('#cuerpo_tareas tr[id^="fila_tarea_"]').length === 0) {
                        $('#cuerpo_tareas').html(`<tr id="fila_vacia"><td colspan="6" class="text-center text-muted py-4"><i class="bi bi-inbox me-2"></i>Sin tareas. ¡Crea la primera!</td></tr>`);
                    }
                    actualizarEstadisticas();
                });
                Swal.fire({ icon: 'success', title: resp.mensaje, timer: 1500, showConfirmButton: false });
            },
            error: function() { Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo eliminar la tarea.' }); }
        });
    });
});

// ==========================================
// SUBTAREAS
// ==========================================
function limpiarErroresSubtarea() {
    $('#form_subtarea .form-control, #form_subtarea .form-select').removeClass('is-invalid');
    $('#form_subtarea .invalid-feedback').text('');
}

let editando_subtarea = false;

$(document).on('click', '.btn-toggle-subtareas', function() {
    const $btn = $(this);
    const id = $btn.data('id');
    const expandido = $btn.data('expandido') == 1;
    $(`.fila-subtarea-padre-${id}`).toggle(!expandido);
    $btn.data('expandido', expandido ? 0 : 1);
    $btn.find('i').toggleClass('bi-chevron-right', expandido).toggleClass('bi-chevron-down', !expandido);
});

$(document).on('click', '.btn-nueva-subtarea', function() {
    editando_subtarea = false;
    const $btn = $(this);
    $('#modal_subtarea_titulo').text(`Nueva Subtarea de: ${$btn.data('tarea-titulo')}`);
    $('#subtarea_id').val('');
    $('#subtarea_padre_id').val($btn.data('tarea-id'));
    $('#form_subtarea')[0].reset();
    limpiarErroresSubtarea();
    poblarCheckboxesTags('contenedor_tags_subtarea', []);
    $('#modal_subtarea').modal('show');
});

$(document).on('click', '.btn-editar-subtarea', function() {
    editando_subtarea = true;
    const $btn = $(this);
    const tags_str = $btn.data('tags') ? String($btn.data('tags')) : '';
    const tags_sel = tags_str ? tags_str.split(',').map(Number).filter(Boolean) : [];
    $('#modal_subtarea_titulo').text('Editar Subtarea');
    $('#subtarea_id').val($btn.data('id'));
    $('#subtarea_padre_id').val($btn.data('padre-id'));
    $('#subtarea_titulo').val($btn.data('titulo'));
    $('#subtarea_descripcion').val($btn.data('descripcion') || '');
    $('#subtarea_prioridad').val($btn.data('prioridad'));
    $('#subtarea_estado').val($btn.data('estado'));
    $('#subtarea_horas').val($btn.data('horas') || '');
    limpiarErroresSubtarea();
    poblarCheckboxesTags('contenedor_tags_subtarea', tags_sel);
    $('#modal_subtarea').modal('show');
});

$('#form_subtarea').on('submit', function(e) {
    e.preventDefault();
    limpiarErroresSubtarea();
    const $btn       = $('#btn_guardar_subtarea');
    const subtarea_id = $('#subtarea_id').val();
    const padre_id   = $('#subtarea_padre_id').val();
    const es_ed      = subtarea_id !== '';
    const url        = es_ed ? `${URL_SUBTAREAS}/${subtarea_id}` : `${URL_TAREAS}/${padre_id}/subtareas`;

    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Guardando...');

    let data = $(this).serialize();
    if (es_ed) data += '&_method=PUT';
    const tag_ids = recogerTagIds('contenedor_tags_subtarea');
    if (es_ed && tag_ids.length === 0) {
        data += '&tags=';
    } else {
        tag_ids.forEach(tid => { data += `&tags[]=${tid}`; });
    }

    $.ajax({
        url: url, method: 'POST', data: data,
        success: function(resp) {
            $('#modal_subtarea').modal('hide');
            const s = resp.subtarea;
            if (es_ed) {
                const $fila = $(`#fila_subtarea_${s.id}`);
                const visible = $fila.is(':visible');
                $fila.replaceWith(construirFilaSubtareaHtml(s));
                if (visible) $(`#fila_subtarea_${s.id}`).show();
            } else {
                const $ultima = $(`.fila-subtarea-padre-${padre_id}`).last();
                const $padre  = $(`#fila_tarea_${padre_id}`);
                const $toggle = $padre.find('.btn-toggle-subtareas');
                const nueva   = $(construirFilaSubtareaHtml(s));

                if ($ultima.length > 0) { $ultima.after(nueva); }
                else { $padre.after(nueva); }
                nueva.show();

                const nuevo_count = $(`.fila-subtarea-padre-${padre_id}`).length;
                $padre.attr('data-subtareas', nuevo_count);

                if ($toggle.length === 0) {
                    $padre.find('td:first .d-inline-block').replaceWith(
                        `<button class="btn btn-sm btn-link p-0 me-1 btn-toggle-subtareas" data-id="${padre_id}" data-expandido="1" title="Ver subtareas"><i class="bi bi-chevron-down"></i></button>`
                    );
                } else {
                    $toggle.data('expandido', 1).find('i').removeClass('bi-chevron-right').addClass('bi-chevron-down');
                }

                const $badge = $padre.find(`.badge-subtareas-${padre_id}`);
                if ($badge.length > 0) { $badge.text(nuevo_count); }
                else { $padre.find('.btn-nueva-subtarea').append(`<span class="badge bg-secondary rounded-pill badge-subtareas-${padre_id}">${nuevo_count}</span>`); }
            }
            Swal.fire({ icon: 'success', title: resp.mensaje, timer: 1500, showConfirmButton: false });
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    $.each(errors, function(campo, mensajes) {
                        $(`#subtarea_${campo}`).addClass('is-invalid');
                        $(`#error_subtarea_${campo}`).text(mensajes[0]);
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.mensaje || 'Error de validación.' });
                }
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un error inesperado.' });
            }
        },
        complete: function() { $btn.prop('disabled', false).html('<i class="bi bi-save me-1"></i>Guardar'); }
    });
});

$(document).on('change', '.select-estado-subtarea-inline', function() {
    const id = $(this).data('id'), estado_nuevo = $(this).val();
    $.ajax({
        url: `${URL_SUBTAREAS}/${id}`, method: 'POST',
        data: { _method: 'PUT', estado: estado_nuevo },
        error: function(xhr) {
            Swal.fire({ icon: 'error', title: 'Transición no permitida', text: xhr.responseJSON?.mensaje || 'Error.' });
            location.reload();
        }
    });
});

$(document).on('click', '.btn-eliminar-subtarea', function() {
    const id = $(this).data('id'), titulo = $(this).data('titulo'), padre_id = $(this).data('padre-id');
    Swal.fire({
        title: '¿Eliminar subtarea?', text: `Se eliminará "${titulo}".`,
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#dc3545', cancelButtonText: 'Cancelar', confirmButtonText: 'Sí, eliminar',
    }).then(function(result) {
        if (!result.isConfirmed) return;
        $.ajax({
            url: `${URL_SUBTAREAS}/${id}`, method: 'POST', data: { _method: 'DELETE' },
            success: function(resp) {
                $(`#fila_subtarea_${id}`).fadeOut(200, function() {
                    $(this).remove();
                    const nuevo_count = $(`.fila-subtarea-padre-${padre_id}`).length;
                    const $padre = $(`#fila_tarea_${padre_id}`);
                    $padre.attr('data-subtareas', nuevo_count);
                    if (nuevo_count === 0) {
                        $padre.find('.btn-toggle-subtareas').replaceWith(`<span class="d-inline-block me-1" style="width:22px;"></span>`);
                        $padre.find(`.badge-subtareas-${padre_id}`).remove();
                    } else {
                        $padre.find(`.badge-subtareas-${padre_id}`).text(nuevo_count);
                    }
                });
                Swal.fire({ icon: 'success', title: resp.mensaje, timer: 1500, showConfirmButton: false });
            },
            error: function() { Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo eliminar la subtarea.' }); }
        });
    });
});

// ==========================================
// TAGS — Inline en modales
// ==========================================
function crearTagInline(nombre, color, contenedor_id, input_nombre_id) {
    $.ajax({
        url: URL_TAGS, method: 'POST', data: { nombre: nombre, color: color },
        success: function(resp) {
            TODOS_LOS_TAGS.push(resp.tag);
            const $c = $(`#${contenedor_id}`);
            $c.find('.text-muted').remove();
            const tag = resp.tag;
            $c.append(`<div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="tags[]"
                       id="tag_check_${contenedor_id}_${tag.id}" value="${tag.id}" checked>
                <label class="form-check-label" for="tag_check_${contenedor_id}_${tag.id}">
                    <span class="badge" style="background-color:${escapeHtml(tag.color)}">${escapeHtml(tag.nombre)}</span>
                </label>
            </div>`);
            $(`#${input_nombre_id}`).val('');
            agregarTagAListaGestion(tag);
        },
        error: function(xhr) {
            Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.mensaje || 'No se pudo crear el tag.' });
        }
    });
}

$('#btn_crear_tag_inline_tarea').on('click', function() {
    const nombre = $('#nuevo_tag_nombre_tarea').val().trim();
    if (!nombre) return;
    crearTagInline(nombre, $('#nuevo_tag_color_tarea').val(), 'contenedor_tags_tarea', 'nuevo_tag_nombre_tarea');
});

$('#btn_crear_tag_inline_subtarea').on('click', function() {
    const nombre = $('#nuevo_tag_nombre_subtarea').val().trim();
    if (!nombre) return;
    crearTagInline(nombre, $('#nuevo_tag_color_subtarea').val(), 'contenedor_tags_subtarea', 'nuevo_tag_nombre_subtarea');
});

// ==========================================
// TAGS — Modal Gestionar Tags
// ==========================================
function agregarTagAListaGestion(tag) {
    const $lista = $('#lista_tags_gestion');
    $lista.find('.text-muted').remove();
    $lista.append(`<div class="d-flex align-items-center justify-content-between mb-2" id="item_tag_${tag.id}">
        <span class="badge fs-6" style="background-color:${escapeHtml(tag.color)}">${escapeHtml(tag.nombre)}</span>
        <button class="btn btn-sm btn-outline-danger btn-eliminar-tag"
                data-id="${tag.id}" data-nombre="${escapeAttr(tag.nombre)}">
            <i class="bi bi-trash"></i>
        </button>
    </div>`);
}

$('#modal_gestionar_tags').on('show.bs.modal', function() {
    const $lista = $('#lista_tags_gestion');
    $lista.empty();
    if (TODOS_LOS_TAGS.length === 0) {
        $lista.html('<p class="text-muted">Sin tags creados aún.</p>');
    } else {
        TODOS_LOS_TAGS.forEach(tag => agregarTagAListaGestion(tag));
    }
    $('#error_tag_gestion').text('');
});

$('#btn_crear_tag_gestion').on('click', function() {
    const nombre = $('#nuevo_tag_nombre_gestion').val().trim();
    const color  = $('#nuevo_tag_color_gestion').val();
    $('#error_tag_gestion').text('');
    if (!nombre) { $('#error_tag_gestion').text('El nombre es obligatorio.'); return; }
    $.ajax({
        url: URL_TAGS, method: 'POST', data: { nombre: nombre, color: color },
        success: function(resp) {
            TODOS_LOS_TAGS.push(resp.tag);
            agregarTagAListaGestion(resp.tag);
            $('#nuevo_tag_nombre_gestion').val('');
        },
        error: function(xhr) {
            $('#error_tag_gestion').text(xhr.responseJSON?.mensaje || 'Error al crear el tag.');
        }
    });
});

$(document).on('click', '.btn-eliminar-tag', function() {
    const id = $(this).data('id'), nombre = $(this).data('nombre');
    Swal.fire({
        title: '¿Eliminar tag?', text: `Se eliminará "${nombre}" de todas las tareas.`,
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#dc3545', cancelButtonText: 'Cancelar', confirmButtonText: 'Sí, eliminar',
    }).then(function(result) {
        if (!result.isConfirmed) return;
        $.ajax({
            url: `${URL_TAGS}/${id}`, method: 'POST', data: { _method: 'DELETE' },
            success: function(resp) {
                $(`#item_tag_${id}`).remove();
                if ($('#lista_tags_gestion').children().length === 0) {
                    $('#lista_tags_gestion').html('<p class="text-muted">Sin tags creados aún.</p>');
                }
                TODOS_LOS_TAGS = TODOS_LOS_TAGS.filter(t => t.id !== id);
                $(`span[data-tag-id="${id}"]`).remove();
                Swal.fire({ icon: 'success', title: resp.mensaje, timer: 1500, showConfirmButton: false });
            },
            error: function() { Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo eliminar el tag.' }); }
        });
    });
});
</script>
@endsection
