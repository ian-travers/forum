<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function notification_is_prepared_when_a_subscribed_thread_get_new_reply_that_in_not_by_current_user()
    {
        $this->signIn();

        /** @var Thread $thread */
        $thread = create(Thread::class);
        $thread->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply here',
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Some reply here',
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }
}
