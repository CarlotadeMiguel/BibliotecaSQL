<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $usuarios = Usuario::paginate(10);
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Role::pluck('name', 'name');
        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email',
            'telefono' => 'nullable|string|max:20',
            'password' => 'required|string|confirmed|min:6',
            'rol' => 'required|exists:roles,name',
        ]);

        try {
            $validated['password'] = bcrypt($validated['password']);
            $usuario = Usuario::create($validated);
            $usuario->syncRoles([$validated['rol']]);
            return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al crear el usuario: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Usuario $usuario)
    {
        return view('usuarios.show', compact('usuario'));
    }

    public function edit(Usuario $usuario)
    {
        $roles = Role::pluck('name', 'name');
        $userRole = $usuario->getRoleNames()->first();
        return view('usuarios.edit', compact('usuario', 'roles', 'userRole'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email,' . $usuario->id,
            'telefono' => 'nullable|string|max:20',
            'rol' => 'required|exists:roles,name',
        ]);

        try {
            $usuario->update($validated);
            $usuario->syncRoles([$validated['rol']]);
            return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al actualizar el usuario: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Usuario $usuario)
    {
        $prestamosActivos = $usuario->prestamos()->whereIn('estado', ['prestado', 'retrasado'])->count();
        if ($prestamosActivos > 0) {
            return back()->withErrors(['error' => 'No se puede eliminar el usuario porque tiene préstamos activos.']);
        }

        try {
            $usuario->delete();
            return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar el usuario: ' . $e->getMessage()]);
        }
    }
}
