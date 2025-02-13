<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsuarioModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 🔹 Iniciar sesión
    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'contrasena_usuario' => 'required|string',
        ]);

        $credentials = [
            'usuario' => $request->usuario,
            'password' => $request->contrasena_usuario,
        ];

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'token' => $token,
            'user' => Auth::user(),
        ]);
    }

    // 🔹 Crear usuario
    public function createUsuario(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string|unique:usuarios,usuario',
            'contrasena_usuario' => 'required|string|min:6',
            'id_rol' => 'required|integer',
            'estado_usuario' => 'required|boolean',
        ]);

        $usuario = UsuarioModels::create([
            'usuario' => $request->usuario,
            'contrasena_usuario' => Hash::make($request->contrasena_usuario), // Encripta la contraseña
            'id_rol' => $request->id_rol,
            'estado_usuario' => $request->estado_usuario,
        ]);

        return response()->json([
            'message' => 'Usuario creado exitosamente',
            'usuario' => $usuario
        ], 201);
    }

    // 🔹 Actualizar usuario
    public function updateUsuario(Request $request, $usuario)
    {
        $usuario = UsuarioModels::where('usuario', $usuario)->first();

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $usuario->update([
            'contrasena_usuario' => $request->has('contrasena_usuario') ? Hash::make($request->contrasena_usuario) : $usuario->contrasena_usuario,
            'id_rol' => $request->id_rol ?? $usuario->id_rol,
            'estado_usuario' => $request->estado_usuario ?? $usuario->estado_usuario,
        ]);

        return response()->json([
            'message' => 'Usuario actualizado exitosamente',
            'usuario' => $usuario
        ]);
    }

    // 🔹 Eliminar usuario (cambiar estado a 0)
    public function deleteUsuario($usuario)
    {
        $usuario = UsuarioModels::where('usuario', $usuario)->first();

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $usuario->update(['estado_usuario' => 0]);

        return response()->json(['message' => 'Usuario eliminado (desactivado) exitosamente']);
    }
}
