<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }

    /** @test */
    function notification_is_prepared_when_a_subscribed_thread_get_new_reply_that_in_not_by_current_user()
    {
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

    /** @test */
    function user_can_fetch_their_unread_notifications()
    {
        $this->signIn();

        /** @var Thread $thread */
        $thread = create(Thread::class);
        $thread->subscribe();

        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Some reply here',
        ]);

        $this->assertCount(1, $this->getJson('/profiles/' . auth()->user()->name . '/notifications')->json());
    }

    /** @test */
    function user_can_mark_notification_as_read()
    {
        /** @var Thread $thread */
        $thread = create(Thread::class);
        $thread->subscribe();

        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Some reply here',
        ]);

        tap(auth()->user(), function ($user) {
            $this->assertCount(1, $user->unreadNotifications);

            $notificationId = $user->unreadNotifications->first()->id;

            $this->delete('/profiles/' . $user->name . '/notifications/' . $notificationId);

            $this->assertCount(0, $user->fresh()->unreadNotifications);
        });
    }
}
