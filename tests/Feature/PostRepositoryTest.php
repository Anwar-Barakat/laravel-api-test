<?php

namespace Tests\Feature;

use App\Exceptions\GeneralJsonException;
use App\Models\Post;
use App\Models\Worker;
use App\Repositories\PostRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostRepositoryTest extends TestCase
{
    public function test_create()
    {
        // 1- Define the goal
        // test if create() will actually create a record in the DB

        // 2- Replicate the env / restriction
        $repository = $this->app->make(PostRepository::class);

        // 3- Define the source of truth
        $dummyWorker    = Worker::factory(1)->create()[0];
        $payload        = [
            'title'         => 'New Post',
            'body'          => [],
            'worker_id'     => $dummyWorker->id,
        ];

        // 4- compare the result
        $result = $repository->create($payload);

        $this->assertSame($payload['title'], $result->title, 'Post created does not have the same title');
    }

    public function test_delete_will_throw_exception_when_delete_post_that_does_not_exist()
    {
        $repository = $this->app->make(PostRepository::class);
        $dummyPost  = Post::factory(1)->make()[0];

        $this->expectException(GeneralJsonException::class);
        $deleted = $repository->delete($dummyPost);
    }

    public function test_update()
    {
        $repository = $this->app->make(PostRepository::class);
        $dummyPost  = Post::factory(1)->create()[0];

        $payload = [
            'title'         => 'Post updated',
            'worker_id'     => 1,
        ];
        $updated = $repository->update($dummyPost, $payload);
        $this->assertSame($payload['title'], $updated->title, 'Post updated does not have the same title');
    }

    public function test_delete()
    {
        $repository = $this->app->make(PostRepository::class);
        $dummyPost  = Post::factory(1)->create()->first();

        $deleted    = $repository->delete($dummyPost);
        $found      = Post::query()->find($dummyPost->id);

        $this->assertSame(null, $found, 'Post is not deleted');
    }
}
