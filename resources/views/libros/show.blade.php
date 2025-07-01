@extends('layouts.app')

@section('title', 'Detalle de Libro')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>👁️ Detalle de Libro</h1>
    <a href="{{ route('libros.index') }}" class="btn btn-secondary">← Volver al listado</a>
</div>

<div class="card">
    <div class="card-body">
        <h2 class="card-title">{{ $libro->titulo }}</h2>
        <p><strong>Autor:</strong> {{ $libro->autor ?? 'Sin autor' }}</p>
        <p><strong>ISBN:</strong> {{ $libro->isbn ?? 'Sin ISBN' }}</p>
        <p>
            <strong>Ejemplares:</strong>
            <span class="badge {{ $libro->ejemplares > 0 ? 'bg-success' : 'bg-danger' }}">
                {{ $libro->ejemplares }}
            </span>
        </p>
    </div>
</div>
@endsection
