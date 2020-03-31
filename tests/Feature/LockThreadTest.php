<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;

class LockThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function locked_thread_may_not_receive_new_replies()
    {
        $this->signIn();

        /** @var Thread $thread */
        $thread = create(Thread::class);

        $thread->locks();

        $this->post($thread->path() . '/replies', [
            'body' => 'FooBar',
            'user_id' => auth()->id(),
        ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    }
}
