<?php

namespace App\Http\Controllers;

use App\Models\LibroMasPrestado;
use App\Models\Prestamo;
use App\Models\Usuario;
use App\Models\Libro;
use Illuminate\Http\Request;

class EstadisticasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Solo el dashboard completo requiere rol admin
        $this->middleware('role:admin')->only('index','librosMasPrestados');
    }

    /* ---------- Dashboard de administración ---------- */
    public function index()
    {
        $estadisticas = [
            'total_libros'       => Libro::count(),
            'total_usuarios'     => Usuario::count(),
            'prestamos_activos'  => Prestamo::whereIn('estado',['prestado','retrasado'])->count(),
            'prestamos_vencidos' => Prestamo::where('fecha_devolucion','<',now())
                                            ->whereIn('estado',['prestado','retrasado'])
                                            ->count(),
        ];

        $librosMasPrestados = LibroMasPrestado::limit(10)->get();

        $usuariosActivos = Usuario::withCount('prestamos')
                                  ->orderByDesc('prestamos_count')
                                  ->limit(5)
                                  ->get();

        return view('estadisticas.index', compact(
            'estadisticas', 'librosMasPrestados', 'usuariosActivos'
        ));
    }

    public function librosMasPrestados()
    {
        $libros = LibroMasPrestado::paginate(15);
        return view('estadisticas.libros-mas-prestados', compact('libros'));
    }

    /* ---------- Estadísticas públicas ---------- */
    public function publicas()
    {
        $librosMasPrestados = LibroMasPrestado::where('total_prestamos','>',0)
                                             ->limit(15)->get();

        $estadisticasPublicas = [
            'total_libros_disponibles' => Libro::where('ejemplares','>',0)->count(),
            'libros_mas_populares'     => $librosMasPrestados->count(),
        ];

        return view('estadisticas.publicas', compact(
            'librosMasPrestados','estadisticasPublicas'
        ));
    }

    public function librosMasPrestadosPublicos()
    {
        $libros = LibroMasPrestado::where('total_prestamos','>',0)
                                  ->paginate(20);

        return view('estadisticas.libros-publicos', compact('libros'));
    }
}
