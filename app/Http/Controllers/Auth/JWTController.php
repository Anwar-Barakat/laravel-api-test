<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class JWTController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'min:3', 'max:50', 'string'],
            'email' => ['required', 'email', 'unique:users,email', 'max:255'],
            'password' => ['required', 'min:8', 'max:25', 'string', 'confirmed'],
            'password_confirmation' => ['required', 'min:8', 'max:55', 'string', 'same:password'],
        ]);

        if ($validator->fails())
            return new JsonResponse($validator->errors()->toJson(), 422);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $token = Auth::login($user);

        return new JsonResponse([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
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
        $token = Auth::attempt($credentials);
        if (!$token)
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);

        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function profile()
    {
        if (!Auth::check()) {
            return new JsonResponse([
                'message' => 'Unauthorized',
            ]);
        }

        return new UserResource(Auth::guard('api')->user());
    }

    public function logout()
    {
        Auth::logout();
        return new JsonResponse([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return new JsonResponse([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
