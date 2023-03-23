<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Repository\UserRepository;
use Illuminate\Http\JsonResponse;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    private UserRepository $_userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->_userRepository = $userRepository;
    }

    public function register(AuthRegisterRequest $request)
    {
        $validated = $request->only('email', 'name', 'password');
        $validated['password'] = bcrypt($validated['password']);

        return $this->_userRepository->create($validated);
    }

    public function login(AuthLoginRequest $request): JsonResponse
    {
        $validated = $request->only('email', 'password');

        if (! $token = JWTAuth::attempt($validated)) {
            return response()->json([ 'message' => 'Invalid login credentials'], 400);
        }

        return response()->json([
            'token' => $token,
        ]);
    }

    public function refreshToken() {
        try {
            JWTAuth::parseToken()->authenticate();
            $token = JWTAuth::refresh();
            return response()->json([ 'token' => $token ]);
        } catch (JWTException $refreshException) {
            return response()->json([ 'message' => $refreshException->getMessage()], 401);
        }
    }


    public function logout(): JsonResponse
    {
        JWTAuth::invalidate();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me(): JsonResponse
    {
        return response()->json(auth('web')->user());
    }
}