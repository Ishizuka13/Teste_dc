<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function get_users()
    {
        try {
            $users = User::all();
            return response()->json($users, 200);
        } catch (\Exception $e) {
            Log::error("Erro ao obter lista de usuários: " . $e->getMessage());
            return response()->json(['error' => 'Erro ao obter lista de usuários'], 500);
        }
    }

    public function login_user(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('UserToken')->accessToken;

                return response()->json(['token' => $token], 200);
            }

            return response()->json(['error' => 'Credenciais inválidas.'], 401);
        } catch (\Exception $e) {
            Log::error("Erro no login de usuário: " . $e->getMessage());
            return response()->json(['error' => 'Erro ao tentar login.'], 500);
        }
    }

    public function login_admin(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $admin = Auth::user();
                $token = $admin->createToken('AdminToken')->accessToken;

                return response()->json(['token' => $token], 200);
            }

            return response()->json(['error' => 'Credenciais inválidas.'], 401);
        } catch (\Exception $e) {
            Log::error("Erro no login de admin: " . $e->getMessage());
            return response()->json(['error' => 'Erro ao tentar login de admin.'], 500);
        }
    }

    public function logout()
    {
        try {
            Auth::logout();
            return response()->json(['message' => 'Logout realizado com sucesso'], 200);
        } catch (\Exception $e) {
            Log::error("Erro ao realizar logout: " . $e->getMessage());
            return response()->json(['error' => 'Erro ao tentar logout.'], 500);
        }
    }
}
