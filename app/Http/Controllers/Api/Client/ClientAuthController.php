<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ClientAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:client', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'min:3', 'max:50', 'string'],
            'email' => ['required', 'email', 'unique:clients,email', 'max:255'],
            'password' => ['required', 'min:8', 'max:25', 'string', 'confirmed'],
            'password_confirmation' => ['required', 'min:8', 'max:55', 'string', 'same:password'],
            'phone' => ['required', 'numeric', 'digits:10'],
        ]);

        if ($validator->fails())
            return new JsonResponse($validator->errors()->toJson(), 422);

        $client = Client::create(array_merge((array)$validator->validated(), ['password' => Hash::make($request->password),]));

        $token = Auth::guard('client')->login($client);

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Client created successfully',
            'client' => $client,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails())
            return new JsonResponse($validator->errors()->toJson(), 422);

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
            'client' => $client,
            'authorisation' => [
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

        return Auth::guard('client')->user();
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
            'authorisation' => [
                'token' => Auth::guard('client')->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
