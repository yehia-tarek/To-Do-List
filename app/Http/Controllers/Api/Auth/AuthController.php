<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Traits\Api\ResponseTrait;
use App\Services\User\IUserService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;

class AuthController extends Controller
{
    use ResponseTrait;

    private $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->userService->create($request->all());
        $token = $user->createToken($request->ip())->plainTextToken;

        $result = [
            'token' => $token,
            'user' => new UserResource($user)
        ];

        return $this->successResponse($result, 'User register successfully.', 201);
    }

    public function login(LoginRequest $request)
    {
        return $this->userService->login($request);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse([], 'User logged out successfully.');
    }

    public function user(Request $request)
    {
        return $this->successResponse(new UserResource($request->user()), 'User retrieved successfully.');
    }
}
