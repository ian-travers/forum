<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function user_can_subscribe_to_threads()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        /** @var Thread $thread */
        $thread = create(Thread::class);

        $this->post($thread->path() . '/subscriptions');

        $this->assertCount(1, $thread->subscriptions);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply here',
        ]);

        // check fo notification
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
