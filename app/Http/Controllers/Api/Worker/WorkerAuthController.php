<?php

namespace App\Http\Controllers\Api\Worker;

use App\Http\Controllers\Controller;
use App\Http\Requests\Worker\LoginWorkerRequest;
use App\Http\Requests\Worker\RegisterWorkerRequest;
use App\Http\Resources\WorkerResource;
use App\Models\Worker;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WorkerAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:worker', ['except' => ['login', 'register']]);
    }

    public function register(RegisterWorkerRequest $request)
    {
        $validation = $request->only(['name', 'email', 'password', 'phone', 'location', 'photo',]);
        $worker = Worker::create(array_merge((array)$validation, ['password' => Hash::make($request->password),]));

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
            'worker' => new WorkerResource($worker),
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function login(LoginWorkerRequest $request)
    {
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
            'worker' => new WorkerResource($worker),
            'authorization' => [
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

        return new WorkerResource(Auth::guard('worker')->user());
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
            'authorization' => [
                'token' => Auth::guard('worker')->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}