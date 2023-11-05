<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistroRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegistroRequest $request) {
        // Validar el registro
        $data = $request->validated();

        // Crear el usuario
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        // Retornar una respuesta
        return [
            'user' => $user,
            'token' => $user->createToken('token')->plainTextToken
        ];
       
    }
    public function login(LoginRequest $request) {
        // Validar el login
        $data = $request->validated();

        // Revisar password
        if(!Auth()->attempt($data)) {
            return response([
                'errors' => ['El email o el password son incorrectos']
            ], 422);
        }

        // Autenticar el usuario
        $user = Auth::user();
        return [
            'user' => $user,
            'token' => $user->createToken('token')->plainTextToken
        ];
   
    }

    public function logout(Request $request) {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return [
            'user' => null
        ];
    }



}
