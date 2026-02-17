<tr id="fila_subtarea_{{ $subtarea->id }}"
    class="fila-subtarea fila-subtarea-padre-{{ $subtarea->tarea_padre_id }} table-light"
    data-id="{{ $subtarea->id }}"
    data-padre-id="{{ $subtarea->tarea_padre_id }}"
    style="display:none;">
    <td class="ps-5">
        <i class="bi bi-arrow-return-right text-muted me-1"></i>
        <strong>{{ $subtarea->titulo }}</strong>
        @if($subtarea->descripcion)
            <br><small class="text-muted ps-4">{{ $subtarea->descripcion }}</small>
        @endif
    </td>
    <td>
        @php
            $clases_prioridad = ['alta' => 'danger', 'media' => 'warning text-dark', 'baja' => 'success'];
        @endphp
        <span class="badge bg-{{ $clases_prioridad[$subtarea->prioridad] ?? 'secondary' }}">
            {{ ucfirst($subtarea->prioridad) }}
        </span>
    </td>
    <td>
        <select class="form-select form-select-sm select-estado-subtarea-inline"
                data-id="{{ $subtarea->id }}" style="min-width:130px;">
            <option value="backlog" {{ $subtarea->estado === 'backlog' ? 'selected' : '' }}>Backlog</option>
            <option value="en_progreso" {{ $subtarea->estado === 'en_progreso' ? 'selected' : '' }}>En Progreso</option>
            <option value="testing" {{ $subtarea->estado === 'testing' ? 'selected' : '' }}>Testing</option>
            <option value="terminada" {{ $subtarea->estado === 'terminada' ? 'selected' : '' }}>Terminada</option>
        </select>
    </td>
    <td>{{ $subtarea->horas_estimadas ? $subtarea->horas_estimadas . 'h' : '-' }}</td>
    <td>
        @foreach($subtarea->tags as $tag)
            <span class="badge me-1" style="background-color:{{ $tag->color }}" data-tag-id="{{ $tag->id }}">{{ $tag->nombre }}</span>
        @endforeach
    </td>
    <td class="text-end">
        <button class="btn btn-sm btn-outline-secondary btn-editar-subtarea me-1"
            data-id="{{ $subtarea->id }}"
            data-padre-id="{{ $subtarea->tarea_padre_id }}"
            data-titulo="{{ $subtarea->titulo }}"
            data-descripcion="{{ $subtarea->descripcion ?? '' }}"
            data-prioridad="{{ $subtarea->prioridad }}"
            data-estado="{{ $subtarea->estado }}"
            data-horas="{{ $subtarea->horas_estimadas ?? '' }}"
            data-tags="{{ $subtarea->tags->pluck('id')->implode(',') }}">
            <i class="bi bi-pencil"></i>
        </button>
        <button class="btn btn-sm btn-outline-danger btn-eliminar-subtarea"
            data-id="{{ $subtarea->id }}"
            data-titulo="{{ $subtarea->titulo }}"
            data-padre-id="{{ $subtarea->tarea_padre_id }}">
            <i class="bi bi-trash"></i>
        </button>
    </td>
</tr>
