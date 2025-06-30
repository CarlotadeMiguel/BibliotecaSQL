<?php

namespace App\Http\Controllers;

//App/Http/Controllers/PrestamoController

use App\Models\Prestamo;
use App\Models\Libro;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PrestamoController extends Controller
{
    public function index()
    {
        $prestamos = Prestamo::with('usuario', 'libro')->get();
        return view('prestamos.index', compact('prestamos'));
    }

    public function create()
    {
        $usuarios = Usuario::all();
        $libros = Libro::all();
        return view('prestamos.create', compact('usuarios', 'libros'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'libro_id' => 'required|exists:libros,id',
            'fecha_prestamo' => 'required|date',
            'fecha_devolucion' => 'nullable|date|after_or_equal:fecha_prestamo',
            // No validar estado, se asigna internamente
        ]);

        try {
            DB::beginTransaction();

            $libro = Libro::lockForUpdate()->findOrFail($validated['libro_id']);
            if ($libro->ejemplares < 1) {
                return back()->withErrors(['libro_id' => 'No hay ejemplares disponibles para préstamo.'])->withInput();
            }

            $validated['estado'] = 'prestado'; // Estado fijo

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
        $usuarios = Usuario::all();
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

            // Si cambia de estado no devuelto a devuelto => incrementa ejemplares
            if (in_array($oldEstado, ['prestado', 'retrasado']) && $nuevoEstado === 'devuelto') {
                $libro = Libro::lockForUpdate()->findOrFail($nuevoLibroId);
                $libro->increment('ejemplares');
            }
            // Si cambia de devuelto a activo => decrementa ejemplares
            elseif ($oldEstado === 'devuelto' && in_array($nuevoEstado, ['prestado', 'retrasado'])) {
                $libro = Libro::lockForUpdate()->findOrFail($nuevoLibroId);
                if ($libro->ejemplares < 1) {
                    DB::rollBack();
                    return back()->withErrors(['libro_id' => 'No hay ejemplares disponibles para préstamo.'])->withInput();
                }
                $libro->decrement('ejemplares');
            }

            // Si cambia el libro y el préstamo está activo (no devuelto), ajustar ejemplares
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
        try {
            DB::beginTransaction();

            if (in_array($prestamo->estado, ['prestado', 'retrasado'])) {
                if ($request->input('confirmar_incremento')) {
                    $libro = Libro::lockForUpdate()->findOrFail($prestamo->libro_id);
                    $libro->increment('ejemplares');
                }
            }

            $prestamo->delete();

            DB::commit();

            return redirect()->route('prestamos.index')->with('success', 'Préstamo eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al eliminar el préstamo: ' . $e->getMessage()]);
        }
    }
}