<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::query()->get();
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $created = User::query()->create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password)
        ]);

        return new UserResource($created);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // $user->update($request->only(['name']));

        $updated = $user->update(['name' => $request->name,]);

        if (!$updated) {
            return new JsonResponse([
                'errors' => ['Failed to update the user']
            ], 400);
        }

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $deleted = $user->forceDelete();

        if (!$deleted) {
            return new JsonResponse([
                'errors' => ['Could not delete resource']
            ], 400);
        }

        return new JsonResponse([
            'data' => 'User deleted successfully'
        ]);
    }
}
