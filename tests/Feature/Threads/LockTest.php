<?php

namespace Tests\Feature\Threads;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class LockTest extends TestCase
{
    use RefreshDatabase;

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
    function administrator_can_unlock_threads()
    {
        $this->signIn(factory(User::class)->states('administrator')->create());

        /** @var Thread $thread */
        $thread = create(Thread::class, ['locked' => true]);

        $this->assertTrue($thread->locked);

        $this->delete(route('locked-threads.destroy', $thread))
            ->assertStatus(Response::HTTP_OK);

        $this->assertFalse($thread->fresh()->locked, 'Failed unlocks thread by Administrator');
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
