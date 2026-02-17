@extends('layouts.app')

@section('title', 'Proyectos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="bi bi-folder2-open me-2"></i>Mis Proyectos</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_proyecto">
        <i class="bi bi-plus-lg me-1"></i>Nuevo Proyecto
    </button>
</div>

<!-- Listado de proyectos -->
<div class="row g-3" id="contenedor_proyectos">
    @forelse($proyectos as $proyecto)
        @include('proyectos._card', ['proyecto' => $proyecto])
    @empty
        <div class="col-12" id="mensaje_vacio">
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox display-4 d-block mb-3"></i>
                <p class="lead">Aún no tienes proyectos. ¡Crea el primero!</p>
            </div>
        </div>
    @endforelse
</div>

<!-- Template de card para JS -->
<template id="tpl_card_proyecto"></template>

<!-- Modal Crear/Editar Proyecto -->
<div class="modal fade" id="modal_proyecto" tabindex="-1" aria-labelledby="modal_proyecto_titulo" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_proyecto_titulo">Nuevo Proyecto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="form_proyecto" novalidate>
                <div class="modal-body">
                    <input type="hidden" id="proyecto_id" value="">

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" maxlength="150" required>
                        <div class="invalid-feedback" id="error_nombre"></div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_limite" class="form-label">Fecha Límite</label>
                        <input type="date" class="form-control" id="fecha_limite" name="fecha_limite">
                        <div class="invalid-feedback" id="error_fecha_limite"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btn_guardar_proyecto">
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
const URL_PROYECTOS = '/proyectos';

// ----- Helpers -----
function limpiarErrores() {
    $('#form_proyecto .form-control').removeClass('is-invalid');
    $('#form_proyecto .invalid-feedback').text('');
}

function mostrarErrores(errors) {
    $.each(errors, function(campo, mensajes) {
        $('#' + campo).addClass('is-invalid');
        $('#error_' + campo).text(mensajes[0]);
    });
}

function construirCardHtml(p) {
    const progreso = p.progreso ?? 0;
    const fecha_ymd = p.fecha_limite ? p.fecha_limite.substring(0, 10) : null;
    const fecha = fecha_ymd ? fecha_ymd.split('-').reverse().join('/') : 'Sin fecha';
    const total = p.tareas_count ?? 0;
    const terminadas = p.tareas_terminadas_count ?? 0;

    return `
    <div class="col-md-4 col-sm-6" id="card_proyecto_${p.id}" data-id="${p.id}">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">${escapeHtml(p.nombre)}</h5>
                <p class="card-text text-muted small">${p.descripcion ? escapeHtml(p.descripcion) : '<em>Sin descripción</em>'}</p>
                <div class="mb-2">
                    <small class="text-muted"><i class="bi bi-calendar3 me-1"></i>${fecha}</small>
                </div>
                <div class="mb-1 d-flex justify-content-between small text-muted">
                    <span>Progreso</span>
                    <span>${terminadas}/${total} tareas</span>
                </div>
                <div class="progress mb-3">
                    <div class="progress-bar bg-success" role="progressbar" style="width: ${progreso}%" aria-valuenow="${progreso}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span class="badge bg-${progreso === 100 ? 'success' : 'secondary'}">${progreso}%</span>
            </div>
            <div class="card-footer bg-transparent d-flex gap-2 justify-content-end">
                <a href="${URL_PROYECTOS}/${p.id}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye me-1"></i>Ver
                </a>
                <button class="btn btn-sm btn-outline-secondary btn-editar-proyecto"
                    data-id="${p.id}"
                    data-nombre="${escapeHtml(p.nombre)}"
                    data-descripcion="${escapeAttr(p.descripcion)}"
                    data-fecha="${fecha_ymd ?? ''}">
                    <i class="bi bi-pencil me-1"></i>Editar
                </button>
                <button class="btn btn-sm btn-outline-danger btn-eliminar-proyecto" data-id="${p.id}" data-nombre="${escapeHtml(p.nombre)}">
                    <i class="bi bi-trash me-1"></i>
                </button>
            </div>
        </div>
    </div>`;
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function escapeAttr(str) {
    if (!str) return '';
    return String(str).replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}

let editando_proyecto = false;

// ----- Abrir modal para crear -----
$('#modal_proyecto').on('show.bs.modal', function() {
    if (!editando_proyecto) {
        $('#modal_proyecto_titulo').text('Nuevo Proyecto');
        $('#proyecto_id').val('');
        $('#form_proyecto')[0].reset();
        limpiarErrores();
    }
    editando_proyecto = false;
});

// ----- Abrir modal para editar -----
$(document).on('click', '.btn-editar-proyecto', function() {
    editando_proyecto = true;
    const $btn = $(this);
    $('#modal_proyecto_titulo').text('Editar Proyecto');
    $('#proyecto_id').val($btn.data('id'));
    $('#nombre').val($btn.data('nombre'));
    $('#descripcion').val($btn.data('descripcion') || '');
    $('#fecha_limite').val($btn.data('fecha') || '');
    limpiarErrores();
    $('#modal_proyecto').modal('show');
});

// ----- Guardar proyecto (crear o editar) -----
$('#form_proyecto').on('submit', function(e) {
    e.preventDefault();
    limpiarErrores();

    const $btn = $('#btn_guardar_proyecto');
    const id = $('#proyecto_id').val();
    const es_edicion = id !== '';
    const url = es_edicion ? `${URL_PROYECTOS}/${id}` : URL_PROYECTOS;
    const method = es_edicion ? 'PUT' : 'POST';

    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Guardando...');

    $.ajax({
        url: url,
        method: method,
        data: $(this).serialize() + (es_edicion ? '&_method=PUT' : ''),
        success: function(resp) {
            $('#modal_proyecto').modal('hide');

            if (es_edicion) {
                // Reemplazar card existente
                $(`#card_proyecto_${resp.proyecto.id}`).replaceWith(construirCardHtml(resp.proyecto));
            } else {
                // Quitar mensaje vacío si existe
                $('#mensaje_vacio').remove();
                // Agregar nueva card al inicio
                $('#contenedor_proyectos').prepend(construirCardHtml(resp.proyecto));
            }

            Swal.fire({ icon: 'success', title: resp.mensaje, timer: 1500, showConfirmButton: false });
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                mostrarErrores(xhr.responseJSON.errors);
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Ocurrió un error inesperado.' });
            }
        },
        complete: function() {
            $btn.prop('disabled', false).html('<i class="bi bi-save me-1"></i>Guardar');
        }
    });
});

// ----- Eliminar proyecto -----
$(document).on('click', '.btn-eliminar-proyecto', function() {
    const $btn = $(this);
    const id = $btn.data('id');
    const nombre = $btn.data('nombre');

    Swal.fire({
        title: '¿Eliminar proyecto?',
        text: `Se eliminará "${nombre}" y todas sus tareas.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Sí, eliminar',
    }).then(function(result) {
        if (result.isConfirmed) {
            $.ajax({
                url: `${URL_PROYECTOS}/${id}`,
                method: 'DELETE',
                success: function(resp) {
                    $(`#card_proyecto_${id}`).fadeOut(300, function() {
                        $(this).remove();
                        if ($('#contenedor_proyectos .col-md-4').length === 0) {
                            $('#contenedor_proyectos').html(`
                                <div class="col-12" id="mensaje_vacio">
                                    <div class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                        <p class="lead">Aún no tienes proyectos. ¡Crea el primero!</p>
                                    </div>
                                </div>`);
                        }
                    });
                    Swal.fire({ icon: 'success', title: resp.mensaje, timer: 1500, showConfirmButton: false });
                },
                error: function() {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo eliminar el proyecto.' });
                }
            });
        }
    });
});
</script>
@endsection
