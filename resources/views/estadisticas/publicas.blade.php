@extends('layouts.app')

@section('title', 'Libros Populares')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>🔥 Libros Más Populares</h1>
    <a href="{{ route('libros.index') }}" class="btn btn-secondary">← Catálogo</a>
</div>

{{-- Top 15 --}}
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0">📈 Top 15</h5>
        <a href="{{ route('estadisticas.libros-publicos') }}" class="btn btn-sm btn-outline-primary">Ver lista completa</a>
    </div>
    <div class="card-body">
        <div class="row">
            @forelse($librosMasPrestados as $index => $libro)
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 border-{{ $index < 3 ? 'warning' : 'secondary' }}">
                        <div class="card-body">
                            <span class="badge bg-{{ $index === 0 ? 'warning text-dark' : 'secondary' }}">#{{ $index+1 }}</span>

                            <h6 class="mt-2">{{ $libro->titulo }}</h6>
                            <p class="small mb-2">Autor: <strong>{{ $libro->autor ?? 'Sin autor' }}</strong></p>

                            <span class="badge bg-primary">{{ $libro->total_prestamos }} préstamos</span>

                            <div class="mt-2">
                                @if($libro->disponibles_actuales > 0)
                                    <span class="badge bg-success">Disponible</span>
                                    <a href="{{ route('prestamos.create', ['libro_id'=>$libro->id]) }}" class="btn btn-sm btn-primary mt-2">📚 Solicitar</a>
                                @else
                                    <span class="badge bg-danger">No disponible</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        Aún no hay datos suficientes.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
