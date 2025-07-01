<?php
namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin')->except(['index', 'show']);
    }

    public function index()
    {
        if (Auth::user()->hasRole('admin')) {
            $libros = Libro::all();
        } else {
            // Usuarios normales solo ven libros disponibles
            $libros = Libro::where('ejemplares', '>', 0)->get();
        }
        return view('libros.index', compact('libros'));
    }

    public function create()
    {
        // Solo admin puede crear libros
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
        // Solo admin puede editar
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
        // Solo admin puede eliminar
        $libro->delete();

        return redirect()->route('libros.index')->with('success', 'Libro eliminado correctamente');
    }
}
