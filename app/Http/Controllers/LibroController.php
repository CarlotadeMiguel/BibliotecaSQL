<?php

namespace App\Http\Controllers;

//App/HttpControllers/LibroController

use App\Models\Libro;
use Illuminate\Http\Request;


class LibroController extends Controller
{
    public function index()
    {
        $libros = Libro::all();
        return view('libros.index', compact('libros'));
    }

    public function create()
    {
        return view('libros.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'nullable|string|max:150',
            'isbn' => 'nullable|string|max:20|unique:libros,isbn',
            'ejemplares' => 'required|integer|min:1',
        ]);

        Libro::create($validated);

        return redirect()->route('libros.index')->with('success', 'Libro creado correctamente');
    }

    public function show(Libro $libro)
    {
        return view('libros.show', compact('libro'));
    }

    public function edit(Libro $libro)
    {
        return view('libros.edit', compact('libro'));
    }

    public function update(Request $request, Libro $libro)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'autor' => 'nullable|string|max:150',
            'isbn' => 'nullable|string|max:20|unique:libros,isbn,' . $libro->id,
            'ejemplares' => 'required|integer|min:1',
        ]);

        $libro->update($validated);

        return redirect()->route('libros.index')->with('success', 'Libro actualizado correctamente');
    }

    public function destroy(Libro $libro)
    {
        // Podrías agregar verificación si el libro tiene préstamos activos antes de eliminar
        $libro->delete();

        return redirect()->route('libros.index')->with('success', 'Libro eliminado correctamente');
    }
}