<?php

namespace App\Repositories;

use App\Exceptions\GeneralJsonException;
use App\Models\Comment;

class CommentRepository extends BaseRepository
{
    public function create(array $attributes)
    {
        $created = Comment::query()->create([
            'body'      => data_get($attributes, 'body'),
            'user_id'   => data_get($attributes, 'user_id'),
            'post_id'   => data_get($attributes, 'post_id'),
        ]);
        throw_if(!$created, GeneralJsonException::class, 'Failed to create');
        return $created;
    }

    public function update($comment, array $attributes)
    {
        $updated = $comment->update([
            'body'      => data_get($attributes, 'body', $comment->body),
            'user_id'   => data_get($attributes, 'user_id', $comment->user_id),
            'post_id'   => data_get($attributes, 'post_id', $comment->post_id),
        ]);

        throw_if(!$updated, GeneralJsonException::class, 'Failed to update');
        return $updated;
    }

    public function delete($comment)
    {
        $deleted = $comment->forceDelete();
        throw_if(!$deleted, GeneralJsonException::class, 'Failed to delete');
        return $deleted;
    }
}