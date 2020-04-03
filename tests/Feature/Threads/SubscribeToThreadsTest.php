<?php

namespace Tests\Feature\Threads;

use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscribeToThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_can_subscribe_to_threads()
    {
        $this->signIn();

        /** @var Thread $thread */
        $thread = create(Thread::class);

        $this->post($thread->path() . '/subscriptions');

        $this->assertCount(1, $thread->subscriptions);
    }

    /** @test */
    function user_can_unsubscribe_from_thread()
    {
        $this->signIn();

        /** @var Thread $thread */
        $thread = create(Thread::class);

        $thread->subscribe();

        $this->assertCount(1, $thread->subscriptions);

        $thread->unsubscribe();

        $this->assertCount(0, $thread->fresh()->subscriptions);
    }
}
