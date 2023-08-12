<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginAdminRequest;
use App\Http\Requests\Admin\RegisterAdminRequest;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin', ['except' => ['login', 'register']]);
    }

    public function register(RegisterAdminRequest $request)
    {
        $validation = $request->only(['name', 'email', 'password',]);
        $admin = Admin::create(array_merge($validation, ['password' => Hash::make($request->password)]));
        $token = Auth::guard('admin')->login($admin);

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Admin created successfully',
            'admin' => $admin,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function login(LoginAdminRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $token = Auth::guard('admin')->attempt($credentials);
        if (!$token)
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);

        $admin = Auth::guard('admin')->user();
        return response()->json([
            'status' => 'success',
            'admin' => $admin,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function profile()
    {
        if (!Auth::guard('admin')->check())
            return new JsonResponse([
                'message' => 'Unauthorized',
            ]);

        return Auth::guard('admin')->user();
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return new JsonResponse([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return new JsonResponse([
            'status' => 'success',
            'admin' => Auth::guard('admin')->user(),
            'authorisation' => [
                'token' => Auth::guard('admin')->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
