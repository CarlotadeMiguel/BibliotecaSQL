<?php

namespace App\Http\Controllers;

//App/Http/Controllers/PrestamoController

use App\Models\Prestamo;
use App\Models\Usuario;
use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PrestamoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->hasRole('admin')) {
            $prestamos = Prestamo::with('usuario', 'libro')->paginate(10);
        } else {
            $prestamos = Prestamo::with('usuario', 'libro')
                ->where('usuario_id', Auth::id())
                ->paginate(10);
        }
        
        return view('prestamos.index', compact('prestamos'));
    }

    public function create()
    {
        if (Auth::user()->hasRole('admin')) {
            $usuarios = Usuario::all();
        } else {
            $usuarios = collect([Auth::user()]);
        }
        
        // Solo libros con ejemplares disponibles
        $libros = Libro::where('ejemplares', '>', 0)->get();
        
        return view('prestamos.create', compact('usuarios', 'libros'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'libro_id' => 'required|exists:libros,id',
            'fecha_prestamo' => 'required|date',
            'fecha_devolucion' => 'nullable|date|after_or_equal:fecha_prestamo',
        ]);

        try {
            DB::beginTransaction();

            $libro = Libro::lockForUpdate()->findOrFail($validated['libro_id']);
            if ($libro->ejemplares < 1) {
                return back()->withErrors(['libro_id' => 'No hay ejemplares disponibles para préstamo.'])->withInput();
            }

            $validated['estado'] = 'prestado';
            Prestamo::create($validated);
            $libro->decrement('ejemplares');

            DB::commit();
            return redirect()->route('prestamos.index')->with('success', 'Préstamo registrado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al registrar el préstamo: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Prestamo $prestamo)
    {
        $prestamo->load('usuario', 'libro');
        return view('prestamos.show', compact('prestamo'));
    }

    public function edit(Prestamo $prestamo)
    {
        if (Auth::user()->hasRole('admin')) {
            $usuarios = Usuario::all();
        } else {
            $usuarios = collect([Auth::user()]);
        }
        
        // Todos los libros (para poder cambiar el libro en edición)
        $libros = Libro::all();
        
        return view('prestamos.edit', compact('prestamo', 'usuarios', 'libros'));
    }

    public function update(Request $request, Prestamo $prestamo)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'libro_id' => 'required|exists:libros,id',
            'fecha_prestamo' => 'required|date',
            'fecha_devolucion' => 'nullable|date|after_or_equal:fecha_prestamo',
            'estado' => 'required|in:prestado,devuelto,retrasado',
        ]);

        try {
            DB::beginTransaction();

            $oldEstado = $prestamo->estado;
            $oldLibroId = $prestamo->libro_id;
            $nuevoEstado = $validated['estado'];
            $nuevoLibroId = $validated['libro_id'];

            $prestamo->update($validated);

            if (in_array($oldEstado, ['prestado', 'retrasado']) && $nuevoEstado === 'devuelto') {
                $libro = Libro::lockForUpdate()->findOrFail($nuevoLibroId);
                $libro->increment('ejemplares');
            } elseif ($oldEstado === 'devuelto' && in_array($nuevoEstado, ['prestado', 'retrasado'])) {
                $libro = Libro::lockForUpdate()->findOrFail($nuevoLibroId);
                if ($libro->ejemplares < 1) {
                    DB::rollBack();
                    return back()->withErrors(['libro_id' => 'No hay ejemplares disponibles para préstamo.'])->withInput();
                }
                $libro->decrement('ejemplares');
            }

            if ($oldLibroId != $nuevoLibroId && in_array($nuevoEstado, ['prestado', 'retrasado'])) {
                $libroViejo = Libro::lockForUpdate()->findOrFail($oldLibroId);
                $libroNuevo = Libro::lockForUpdate()->findOrFail($nuevoLibroId);

                $libroViejo->increment('ejemplares');
                if ($libroNuevo->ejemplares < 1) {
                    DB::rollBack();
                    return back()->withErrors(['libro_id' => 'No hay ejemplares disponibles en el libro nuevo para préstamo.'])->withInput();
                }
                $libroNuevo->decrement('ejemplares');
            }

            DB::commit();
            return redirect()->route('prestamos.index')->with('success', 'Préstamo actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar el préstamo: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Request $request, Prestamo $prestamo)
    {
        // Si está aún prestado, no se elimina
        if ($prestamo->estado === 'prestado') {
            return back()->withErrors([
                'error' => 'No se puede eliminar un préstamo mientras siga en estado “prestado”.'
            ]);
        }
    
        try {
            DB::beginTransaction();
    
            // Si está devuelto o retrasado, al eliminar se reincrementa stock
            if (in_array($prestamo->estado, ['devuelto', 'retrasado'])) {
                $libro = Libro::lockForUpdate()->findOrFail($prestamo->libro_id);
                $libro->increment('ejemplares');
            }
    
            $prestamo->delete();
            DB::commit();
    
            return redirect()->route('prestamos.index')
                             ->with('success', 'Préstamo eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([
                'error' => 'Error al eliminar el préstamo: ' . $e->getMessage()
            ]);
        }
    }

    /**
 * Mostrar préstamos vencidos (no devueltos tras fecha_devolucion)
 */
public function vencidos()
{
    $prestamosVencidos = Prestamo::with(['usuario','libro'])
        ->where('fecha_devolucion', '<', now())
        ->where('estado', '!=', 'devuelto')
        ->orderBy('fecha_devolucion', 'asc')
        ->paginate(15);

    return view('prestamos.vencidos', compact('prestamosVencidos'));
}

/**
 * Marcar automáticamente como 'retrasado' los préstamos vencidos
 */
public function marcarVencidos()
{
    $affected = Prestamo::where('fecha_devolucion', '<', now())
        ->where('estado', 'prestado')
        ->update(['estado' => 'retrasado']);

    return back()->with('success', "Se han marcado {$affected} préstamos como retrasados.");
}



}
