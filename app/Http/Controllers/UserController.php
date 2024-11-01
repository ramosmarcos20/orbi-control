<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user_list = User::get();
        return view('user.index', compact('user_list'));
    }

    public function store(Request $request)
    {
        // Validar los datos
        $request->validate([
            'name'      => 'required|string|max:100|unique:users,name',
            'password'  => 'required|string|min:6',
        ]);

        try {
            User::create([
                'name'      => $request->name,
                'password'  => Hash::make($request->password),
            ]);
    
            return response()->json([
                'message' => 'Usuario creado exitosamente'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear el usuario: ' . $e->getMessage()
            ], 500);
        }

    }

    public function edit($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:users,name,' . $id,
            'password' => 'nullable|string|min:6',
        ]);
    
        try {

            $user = User::find($id);
            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }
    
            $user->name = $request->input('name');
    
            if ($request->filled('password')) {
                $user->password = bcrypt($request->input('password'));
            }
    
            $user->save();
    
            return response()->json([
                'success' => 'Usuario actualizado correctamente'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error en la actualización: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'error' => 'Usuario no encontrado'
            ], 404);
        }

        try {
            $user->delete();
            return response()->json([
                'message' => 'Usuario eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al eliminar la empresa: ' . $e->getMessage()
            ], 500);
        }

    }
    
}

