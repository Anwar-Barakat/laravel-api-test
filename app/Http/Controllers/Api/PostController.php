<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::query()->get();

        return new JsonResponse([
            'data' => $posts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $created = Post::query()->create([
            'title' => $request->title,
            'body'  => $request->body
        ]);

        return new JsonResponse([
            'data' => $created
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return new JsonResponse([
            'data'  => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // $post->update($request->only(['title', 'body']));

        $updated = $post->update([
            'title' => $request->title ?? $post->title,
            'body'  => $request->body ?? $post->body,
        ]);

        if (!$updated) {
            return new JsonResponse([
                'errors' => 'Failed to update the post'
            ], 400);
        }

        return new JsonResponse([
            'data' => $post
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $deleted = $post->forceDelete();

        if (!$deleted) {
            return new JsonResponse([
                'errors' => 'Could not delete resource'
            ], 400);
        }

        return new JsonResponse([
            'data' => 'Post deleted successfully'
        ]);
    }
}
