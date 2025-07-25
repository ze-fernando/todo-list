<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    private function generateToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }

    private function deleteToken(User $user): void
    {
        $hasToken = $user->currentAccessToken();
        if ($hasToken) {
            $hasToken->delete();
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:8'
            ]);

            if (!Auth::attempt($data)) {
                return response()->json([
                    'message' => 'Email e senha incorretos, verifique as credenciais ou crie um conta'
                ], 401);
            }

            $user = Auth::user();

            $token = $this->generateToken($user);

            return response()->json(['user' => $user, 'access_token' => $token, 'token_type' => 'Bearer']);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json(['erro' => $th->getMessage()]);
        }
    }

    public function register(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
            ]);

            $user = User::create($data);

            if ($user) {
                $token = $this->generateToken($user);
            }

            return response()->json(['user' => $user, 'access_token' => $token, 'token_type' => 'Bearer']);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json(['error' => $th->getMessage()]);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();

            $this->deleteToken($user);

            return response()->json([
                'message' => 'Logout realizado com sucesso'
            ]);
        } catch (\Throwable $th) {
            Log::error('Erro no logout: ' . $th->getMessage());

            return response()->json([
                'message' => 'Erro ao tentar fazer logout'
            ], 500);
        }
    }
}
