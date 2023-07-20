<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::query()->get();

        return new JsonResponse([
            'data' => $comments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $created = Comment::query()->create([
            'body'      => $request->body,
            'post_id'   => $request->post_id,
            'user_id'   => $request->user_id,
        ]);

        return new JsonResponse([
            'data' => $created
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return new JsonResponse([
            'data'  => $comment
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        // $comment->update($request->only(['title', 'body']));

        $updated = $comment->update([
            'body'      => $request->body,
            'body'      => $request->body ?? $comment->body,
            'post_id'   => $request->post_id ?? $comment->post_id,
            'user_id'   => $request->user_id ?? $comment->user_id,
        ]);

        if (!$updated) {
            return new JsonResponse([
                'errors' => 'Failed to update the comment'
            ], 400);
        }

        return new JsonResponse([
            'data' => $comment
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $deleted = $comment->forceDelete();

        if (!$deleted) {
            return new JsonResponse([
                'errors' => 'Could not delete resource'
            ], 400);
        }

        return new JsonResponse([
            'data' => 'Comment deleted successfully'
        ]);
    }
}
