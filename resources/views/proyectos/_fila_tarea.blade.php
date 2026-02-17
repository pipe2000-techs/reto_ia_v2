<tr id="fila_tarea_{{ $tarea->id }}" data-id="{{ $tarea->id }}"
    data-subtareas="{{ $tarea->subtareas->count() }}">
    <td>
        @if($tarea->subtareas->count() > 0)
            <button class="btn btn-sm btn-link p-0 me-1 btn-toggle-subtareas"
                    data-id="{{ $tarea->id }}"
                    data-expandido="0"
                    title="Ver subtareas">
                <i class="bi bi-chevron-right"></i>
            </button>
        @else
            <span class="d-inline-block me-1" style="width:22px;"></span>
        @endif

        <strong>{{ $tarea->titulo }}</strong>

        <button class="btn btn-link btn-sm p-0 ms-1 btn-nueva-subtarea text-secondary"
                data-tarea-id="{{ $tarea->id }}"
                data-tarea-titulo="{{ $tarea->titulo }}"
                title="Agregar subtarea">
            <i class="bi bi-node-plus"></i>
            @if($tarea->subtareas->count() > 0)
                <span class="badge bg-secondary rounded-pill badge-subtareas-{{ $tarea->id }}">{{ $tarea->subtareas->count() }}</span>
            @endif
        </button>

        @if($tarea->descripcion)
            <br><small class="text-muted ps-4">{{ $tarea->descripcion }}</small>
        @endif
    </td>
    <td>
        @php
            $clases_prioridad = ['alta' => 'danger', 'media' => 'warning text-dark', 'baja' => 'success'];
        @endphp
        <span class="badge bg-{{ $clases_prioridad[$tarea->prioridad] ?? 'secondary' }}">
            {{ ucfirst($tarea->prioridad) }}
        </span>
    </td>
    <td>
        <select class="form-select form-select-sm select-estado-inline" data-id="{{ $tarea->id }}" style="min-width:130px;">
            <option value="backlog" {{ $tarea->estado === 'backlog' ? 'selected' : '' }}>Backlog</option>
            <option value="en_progreso" {{ $tarea->estado === 'en_progreso' ? 'selected' : '' }}>En Progreso</option>
            <option value="testing" {{ $tarea->estado === 'testing' ? 'selected' : '' }}>Testing</option>
            <option value="terminada" {{ $tarea->estado === 'terminada' ? 'selected' : '' }}>Terminada</option>
        </select>
    </td>
    <td>{{ $tarea->horas_estimadas ? $tarea->horas_estimadas . 'h' : '-' }}</td>
    <td>
        @foreach($tarea->tags as $tag)
            <span class="badge me-1" style="background-color:{{ $tag->color }}" data-tag-id="{{ $tag->id }}">{{ $tag->nombre }}</span>
        @endforeach
    </td>
    <td class="text-end">
        <button class="btn btn-sm btn-outline-secondary btn-editar-tarea me-1"
            data-id="{{ $tarea->id }}"
            data-titulo="{{ $tarea->titulo }}"
            data-descripcion="{{ $tarea->descripcion ?? '' }}"
            data-prioridad="{{ $tarea->prioridad }}"
            data-estado="{{ $tarea->estado }}"
            data-horas="{{ $tarea->horas_estimadas ?? '' }}"
            data-tags="{{ $tarea->tags->pluck('id')->implode(',') }}">
            <i class="bi bi-pencil"></i>
        </button>
        <button class="btn btn-sm btn-outline-danger btn-eliminar-tarea"
            data-id="{{ $tarea->id }}"
            data-titulo="{{ $tarea->titulo }}">
            <i class="bi bi-trash"></i>
        </button>
    </td>
</tr>

{{-- Subtareas de esta tarea (ocultas por defecto, se muestran con el toggle) --}}
@foreach($tarea->subtareas as $subtarea)
    @include('proyectos._fila_subtarea', ['subtarea' => $subtarea])
@endforeach
