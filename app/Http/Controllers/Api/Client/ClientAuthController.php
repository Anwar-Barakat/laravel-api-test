<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\LoginClientRequest;
use App\Http\Requests\Client\RegisterClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:client', ['except' => ['login', 'register']]);
    }

    public function register(RegisterClientRequest $request)
    {
        $validation = $request->only(['name', 'email', 'phone']);
        $client = Client::create(array_merge((array)$validation, ['password' => Hash::make($request->password),]));
        $token = Auth::guard('client')->login($client);

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Client created successfully',
            'client' => new ClientResource($client),
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function login(LoginClientRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $token = Auth::guard('client')->attempt($credentials);
        if (!$token)
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);

        $client = Auth::guard('client')->user();
        return response()->json([
            'status' => 'success',
            'client' => new ClientResource($client),
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function profile()
    {
        if (!Auth::guard('client')->check())
            return new JsonResponse([
                'message' => 'Unauthorized',
            ]);

        return new ClientResource(Auth::guard('client')->user());
    }

    public function logout()
    {
        Auth::guard('client')->logout();
        return new JsonResponse([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return new JsonResponse([
            'status' => 'success',
            'client' => Auth::guard('client')->user(),
            'authorization' => [
                'token' => Auth::guard('client')->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
