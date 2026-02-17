<div class="col-md-4 col-sm-6" id="card_proyecto_{{ $proyecto->id }}" data-id="{{ $proyecto->id }}">
    <div class="card h-100">
        <div class="card-body">
            <h5 class="card-title">{{ $proyecto->nombre }}</h5>
            <p class="card-text text-muted small">
                {{ $proyecto->descripcion ?? '' }}
                @if(!$proyecto->descripcion)<em>Sin descripción</em>@endif
            </p>
            <div class="mb-2">
                <small class="text-muted">
                    <i class="bi bi-calendar3 me-1"></i>
                    {{ $proyecto->fecha_limite ? $proyecto->fecha_limite->format('d/m/Y') : 'Sin fecha' }}
                </small>
            </div>
            @php
                $total      = $proyecto->tareas_count ?? 0;
                $terminadas = $proyecto->tareas_terminadas_count ?? 0;
                $progreso   = $proyecto->progreso ?? 0;
            @endphp
            <div class="mb-1 d-flex justify-content-between small text-muted">
                <span>Progreso</span>
                <span>{{ $terminadas }}/{{ $total }} tareas</span>
            </div>
            <div class="progress mb-3">
                <div class="progress-bar bg-success" role="progressbar"
                    style="width: {{ $progreso }}%"
                    aria-valuenow="{{ $progreso }}" aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
            <span class="badge bg-{{ $progreso === 100 ? 'success' : 'secondary' }}">{{ $progreso }}%</span>
        </div>
        <div class="card-footer bg-transparent d-flex gap-2 justify-content-end">
            <a href="{{ route('proyectos.show', $proyecto) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-eye me-1"></i>Ver
            </a>
            <button class="btn btn-sm btn-outline-secondary btn-editar-proyecto"
                data-id="{{ $proyecto->id }}"
                data-nombre="{{ $proyecto->nombre }}"
                data-descripcion="{{ $proyecto->descripcion }}"
                data-fecha="{{ $proyecto->fecha_limite ? $proyecto->fecha_limite->format('Y-m-d') : '' }}">
                <i class="bi bi-pencil me-1"></i>Editar
            </button>
            <button class="btn btn-sm btn-outline-danger btn-eliminar-proyecto"
                data-id="{{ $proyecto->id }}"
                data-nombre="{{ $proyecto->nombre }}">
                <i class="bi bi-trash me-1"></i>
            </button>
        </div>
    </div>
</div>
