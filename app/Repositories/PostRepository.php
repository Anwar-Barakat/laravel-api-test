<?php

namespace App\Repositories;

use App\Exceptions\GeneralJsonException;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostRepository extends BaseRepository
{
    public function create(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $created = Post::query()->create([
                'title' => data_get($attributes, 'title', 'untitled'),
                'body'  => data_get($attributes, 'body',)
            ]);

            throw_if(!$created, GeneralJsonException::class, 'Failed to create');

            if ($user_ids = data_get($attributes, 'user_ids'))
                $created->users()->sync($user_ids);

            return $created;
        });
    }

    public function update($post, array $attributes)
    {
        return DB::transaction(function () use ($post, $attributes) {
            $updated =  $post->update([
                'title' => data_get($attributes, 'title', $post->title),
                'body'  => data_get($attributes, 'body', $post->body),
            ]);

            throw_if(!$updated, GeneralJsonException::class, 'Failed to update');

            if ($user_ids = data_get($attributes, 'user_ids'))
                $post->users()->sync($user_ids);

            return $post;
        });
    }

    public function delete($post)
    {
        return DB::transaction(function () use ($post) {
            $deleted = $post->forceDelete();
            throw_if(!$deleted, GeneralJsonException::class, 'Can not delete post');
            return $deleted;
        });
    }
}