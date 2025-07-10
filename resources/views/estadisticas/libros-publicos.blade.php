@extends('layouts.app')

@section('title', 'Ranking de Libros')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📊 Ranking Completo</h1>
    <a href="{{ route('estadisticas.publicas') }}" class="btn btn-secondary">← Resumen</a>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th><th>Título</th><th>Autor</th><th>Veces prestado</th><th>Estado</th><th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($libros as $index => $libro)
                @php $pos = ($libros->currentPage()-1)*$libros->perPage()+$index+1; @endphp
                <tr>
                    <td><strong>#{{ $pos }}</strong></td>
                    <td>{{ $libro->titulo }}</td>
                    <td>{{ $libro->autor ?? 'Sin autor' }}</td>
                    <td><span class="badge bg-primary">{{ $libro->total_prestamos }}</span></td>
                    <td>
                        @if($libro->disponibles_actuales>0)
                            <span class="badge bg-success">Disponible ({{ $libro->disponibles_actuales }})</span>
                        @else
                            <span class="badge bg-danger">No disponible</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('libros.show',$libro->id) }}" class="btn btn-sm btn-info">👁️ Ver</a>
                        @if($libro->disponibles_actuales>0)
                            <a href="{{ route('prestamos.create',['libro_id'=>$libro->id]) }}" class="btn btn-sm btn-primary">📚 Prestar</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">No hay datos</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $libros->links() }}
@endsection
