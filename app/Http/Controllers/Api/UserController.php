<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mail;

/**
 * @group User Management
 *
 * APIs to manage the user resource.
 **/
class UserController extends Controller
{
    /**
     * Display a listing of users.
     *
     * Get a list of users.
     *
     * @queryParam page_size int Size per page. Defaults to 20. Example: 20
     * @queryParam page int Page to view. Example: 1
     *
     * @apiResourceCollection App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     */
    public function index(Request $request)
    {
        $pageSize   = $request->page_size ?? 20;
        $users      = User::query()->paginate($pageSize);
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @bodyParam name string required Name of the user. Example : John Doe
     * @bodyParam email string required Email of the user. Example : john@example.com
     * @apiResourceCollection App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     */
    public function store(Request $request, UserRepository $repository)
    {
        $created = $repository->create($request->only([
            'name', 'email', 'password'
        ]));
        return new UserResource($created);
    }

    /**
     * Display the specified user.
     *
     * @urlParam id int required User ID
     * @apiResourceCollection App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     * @bodyParam name string Name of the user. Example : John Doe
     * @apiResourceCollection App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     */
    public function update(Request $request, User $user, UserRepository $repository)
    {
        $user = $repository->update($user, $request->only('name'));
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @response 200 {
     *  "data" : "success"
     * }
     */
    public function destroy(User $user, UserRepository $repository)
    {
        $repository->delete($user);
        return new JsonResponse([
            'data' => 'User deleted successfully'
        ]);
    }

    public function send()
    {
        $user = User::factory()->create();
        dd($user);
        Mail::to();
    }
}
