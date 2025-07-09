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
            : Libro::where('ejemplares','>',0)->paginate(10);

        return view('libros.index',compact('libros'));
    }

    public function show(Libro $libro)
    {
        return view('libros.show',compact('libro'));
    }

    // Otros métodos create/store/edit/update/destroy…

    // 1) Libros disponibles
    public function disponibles()
    {
        $librosDisponibles = Libro::where('ejemplares','>',0)->paginate(10);
        return view('libros.disponibles',compact('librosDisponibles'));
    }

    // 2) Disponibilidad detallada con JOIN
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
}
