<?php

namespace App\Http\Controllers\Api\Worker;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class WorkerAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:worker', ['except' => ['login', 'register']]);
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'min:3', 'max:50', 'string'],
            'email' => ['required', 'email', 'unique:workers,email', 'max:255'],
            'password' => ['required', 'min:8', 'max:25', 'string', 'confirmed'],
            'password_confirmation' => ['required', 'min:8', 'max:55', 'string', 'same:password'],
            'phone' => ['required', 'numeric', 'digits:10'],
            'location' => ['required', 'min:10', 'max:255', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif'],
        ]);

        if ($validator->fails())
            return new JsonResponse($validator->errors()->toJson(), 422);

        $worker = Worker::create(array_merge((array)$validator->validated(), ['password' => Hash::make($request->password),]));

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filePath = $file->store('workers');
            $worker->photo = $filePath;
            $worker->save();
        }

        $token = Auth::guard('worker')->login($worker);

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Worker created successfully',
            'worker' => $worker,
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
        $token = Auth::guard('worker')->attempt($credentials);
        if (!$token)
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);

        $worker = Auth::guard('worker')->user();
        return response()->json([
            'status' => 'success',
            'worker' => $worker,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function profile()
    {
        if (!Auth::guard('worker')->check())
            return new JsonResponse([
                'message' => 'Unauthorized',
            ]);

        return Auth::guard('worker')->user();
    }

    public function logout()
    {
        Auth::guard('worker')->logout();
        return new JsonResponse([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return new JsonResponse([
            'status' => 'success',
            'worker' => Auth::guard('worker')->user(),
            'authorisation' => [
                'token' => Auth::guard('worker')->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
