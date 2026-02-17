<tr id="fila_tarea_{{ $tarea->id }}" data-id="{{ $tarea->id }}">
    <td>
        <strong>{{ $tarea->titulo }}</strong>
        @if($tarea->descripcion)
            <br><small class="text-muted">{{ $tarea->descripcion }}</small>
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
    <td class="text-end">
        <button class="btn btn-sm btn-outline-secondary btn-editar-tarea me-1"
            data-id="{{ $tarea->id }}"
            data-titulo="{{ $tarea->titulo }}"
            data-descripcion="{{ $tarea->descripcion }}"
            data-prioridad="{{ $tarea->prioridad }}"
            data-estado="{{ $tarea->estado }}"
            data-horas="{{ $tarea->horas_estimadas ?? '' }}">
            <i class="bi bi-pencil"></i>
        </button>
        <button class="btn btn-sm btn-outline-danger btn-eliminar-tarea"
            data-id="{{ $tarea->id }}"
            data-titulo="{{ $tarea->titulo }}">
            <i class="bi bi-trash"></i>
        </button>
    </td>
</tr>
