<?php

namespace App\Repositories;

use App\Exceptions\GeneralJsonException;
use App\Models\Admin;
use App\Models\Post;
use App\Notifications\AddedPostNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class PostRepository extends BaseRepository
{
    public function create(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $created = Post::query()->create([
                'title'         => data_get($attributes, 'title', 'untitled'),
                'body'          => data_get($attributes, 'body'),
                'worker_id'     => data_get($attributes, 'worker_id'),
            ]);

            throw_if(!$created, GeneralJsonException::class, 'Failed to create');

            if ($user_ids = data_get($attributes, 'user_ids'))
                $created->users()->sync($user_ids);

            $this->sendAdminNotification($created);

            return $created;
        });
    }

    public function update($post, array $attributes)
    {
        return DB::transaction(function () use ($post, $attributes) {
            $updated =  $post->update([
                'title'         => data_get($attributes, 'title', $post->title),
                'body'          => data_get($attributes, 'body', $post->body),
                'worker_id'     => data_get($attributes, 'worker_id'),

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

    public function sendAdminNotification($post)
    {
        $admins = Admin::get();
        Notification::send($admins, new AddedPostNotification(auth()->guard('worker')->user(), $post));
    }
}
