<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LibroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin')->except(['index','show','disponibles','disponibilidadDetallada']);
    }

    public function index()
    {
        $libros = Auth::user()->hasRole('admin')
            ? Libro::paginate(10)
            : Libro::where('ejemplares', '>', 0)->paginate(10);

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
        ], [
            'titulo.required' => 'El título es obligatorio.',
            'titulo.max' => 'El título no puede tener más de 255 caracteres.',
            'autor.max' => 'El autor no puede tener más de 150 caracteres.',
            'isbn.unique' => 'Este ISBN ya está registrado en otro libro.',
            'isbn.max' => 'El ISBN no puede tener más de 20 caracteres.',
            'ejemplares.required' => 'El número de ejemplares es obligatorio.',
            'ejemplares.min' => 'Debe haber al menos 1 ejemplar.',
            'ejemplares.integer' => 'El número de ejemplares debe ser un número entero.',
        ]);

        try {
            Libro::create($validated);
            return redirect()->route('libros.index')
                ->with('success', '📚 Libro creado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al crear el libro: ' . $e->getMessage()])
                ->withInput();
        }
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
            'ejemplares' => 'required|integer|min:0',
        ], [
            'titulo.required' => 'El título es obligatorio.',
            'titulo.max' => 'El título no puede tener más de 255 caracteres.',
            'autor.max' => 'El autor no puede tener más de 150 caracteres.',
            'isbn.unique' => 'Este ISBN ya está registrado en otro libro.',
            'isbn.max' => 'El ISBN no puede tener más de 20 caracteres.',
            'ejemplares.required' => 'El número de ejemplares es obligatorio.',
            'ejemplares.min' => 'No puede haber menos de 0 ejemplares.',
            'ejemplares.integer' => 'El número de ejemplares debe ser un número entero.',
        ]);

        try {
            // Verificar si se está reduciendo ejemplares por debajo de préstamos activos
            $prestamosActivos = $libro->prestamos()
                ->whereIn('estado', ['prestado', 'retrasado'])
                ->count();

            if ($validated['ejemplares'] < $prestamosActivos) {
                return back()->withErrors([
                    'ejemplares' => "No puedes reducir los ejemplares a {$validated['ejemplares']} porque hay {$prestamosActivos} préstamos activos."
                ])->withInput();
            }

            $libro->update($validated);
            return redirect()->route('libros.index')
                ->with('success', '✏️ Libro actualizado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al actualizar el libro: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(Libro $libro)
    {
        try {
            // Verificar si tiene préstamos activos
            $prestamosActivos = $libro->prestamos()
                ->whereIn('estado', ['prestado', 'retrasado'])
                ->count();

            if ($prestamosActivos > 0) {
                return back()->withErrors([
                    'error' => "No se puede eliminar el libro porque tiene {$prestamosActivos} préstamos activos."
                ]);
            }

            $libro->delete();
            return redirect()->route('libros.index')
                ->with('success', '🗑️ Libro eliminado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar el libro: ' . $e->getMessage()]);
        }
    }

    // Métodos de disponibilidad 
    public function disponibles()
    {
        $librosDisponibles = Libro::where('ejemplares', '>', 0)->paginate(10);
        return view('libros.disponibles', compact('librosDisponibles'));
    }

    public function disponibilidadDetallada()
    {
        $libros = DB::table('libros as l')
            ->leftJoin('prestamos as p', function($join) {
                $join->on('l.id', '=', 'p.libro_id')
                     ->whereIn('p.estado', ['prestado', 'retrasado']);
            })
            ->select(
                'l.id',
                'l.titulo',
                'l.autor',
                'l.ejemplares AS stock_actual',
                DB::raw('COUNT(p.id) AS prestamos_activos'),
                DB::raw('(l.ejemplares + COUNT(p.id)) AS total_inicial')
            )
            ->groupBy('l.id','l.titulo','l.autor','l.ejemplares')
            ->paginate(10);

        return view('libros.disponibilidad-detallada', compact('libros'));
    }

    public function buscar(Request $request)
{
    // Recoge el término de búsqueda
    $q = $request->input('q');

    // Construye la consulta Eloquent
    $libros = Libro::when($q, function($query, $q) {
            $query->where(function($sub) use ($q) {
                $sub->where('titulo', 'LIKE', "%{$q}%")
                    ->orWhere('autor', 'LIKE', "%{$q}%")
                    ->orWhere('isbn', 'LIKE', "%{$q}%");
            });
        })
        ->orderBy('titulo')
        ->paginate(10)
        ->appends(['q' => $q]);

    return view('libros.resultados', compact('libros','q'));
}
}
