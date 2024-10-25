<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();

            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = bcrypt($data['password']);
            $user->save();

            return response()->json([
                'message' => 'Usuário criado com sucesso!'
            ], 201);
        } catch (Exception $e) {
            Log::error('Erro ao criar usuário: ' . $e->getMessage());

            return response()->json([
                'error' => 'Erro ao criar usuário. Por favor, tente novamente mais tarde.'
            ], 500);
        }
    }
}
