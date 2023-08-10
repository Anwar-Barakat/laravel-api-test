<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'min:3', 'max:50', 'string'],
            'email' => ['required', 'email', 'unique:admins,email', 'max:255'],
            'password' => ['required', 'min:8', 'max:25', 'string', 'confirmed'],
            'password_confirmation' => ['required', 'min:8', 'max:55', 'string', 'same:password'],
        ]);

        if ($validator->fails())
            return new JsonResponse($validator->errors()->toJson(), 422);

        $admin = Admin::create(array_merge($validator->validated(), ['password' => Hash::make($request->password)]));
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

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails())
            return new JsonResponse($validator->errors()->toJson(), 422);

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
