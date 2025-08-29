<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticationController
{
    public function login(LoginRequest $request): Response|JsonResponse
    {
        if (!Auth::attempt($request->validated())) {
            return response()->json([
                'error' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        session()->regenerate();

        return response()->noContent();
    }

    public function logout(): Response
    {
        auth()->logout();

        return response()->noContent();
    }
}
