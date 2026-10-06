<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(array $dados): array
    {
        $user = User::where('email', $dados['email'])->first();

        if (!$user || !Hash::check($dados['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'As credenciais informadas são inválidas.',
            ]);
        }

        $token = $user
            ->createToken('api-token')
            ->plainTextToken;

        return [
            'user' => $user->load('roles', 'permissions'),
            'token' => $token,
        ];
    }

    public function logout(User $user): void
    {
        $token = $user->currentAccessToken();
        if ($token instanceof PersonalAccessToken) {
            $token->delete();
        }
    }
    
}
