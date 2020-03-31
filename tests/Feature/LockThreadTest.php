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
    function non_administrator_may_not_lock_threads()
    {
        $this->signIn();

        /** @var Thread $thread */
        $thread = create(Thread::class);

        $this->post(route('locked-threads.store', $thread), [
            'locked' => true,
        ])->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertFalse($thread->fresh()->locked);
    }

    /** @test */
    function administrator_can_lock_threads()
    {
        $this->signIn(factory(User::class)->states('administrator')->create());

        /** @var Thread $thread */
        $thread = create(Thread::class);

        $this->post(route('locked-threads.store', $thread))
            ->assertStatus(Response::HTTP_OK);

        $this->assertTrue($thread->fresh()->locked, 'Failed locks thread by Administrator');
    }

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
