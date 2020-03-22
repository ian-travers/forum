<?php

namespace Tests\Unit;

use App\Channel;
use App\Notifications\ThreadWasUpdated;
use App\Thread;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Thread
     */
    protected $thread;

    protected function setUp(): void
    {
        parent::setUp();

        $this->thread = create(Thread::class);
    }

    /** @test */
    function thread_has_valid_path()
    {
        $this->assertEquals('/threads/' . $this->thread->channel->slug . '/' . $this->thread->id, $this->thread->path());
    }

    /** @test */
    function thread_has_replies()
    {
        $this->assertInstanceOf(Collection::class, $this->thread->replies);
    }

    /** @test */
    function thread_has_a_creator()
    {
        $this->assertInstanceOf(User::class, $this->thread->creator);
    }

    /** @test */
    function thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1,
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    function thread_notify_all_registered_subscribers_when_a_reply_is_added()
    {
        Notification::fake();


        $this->signIn();
        $this->thread->subscribe();
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1,
        ]);

        $user = create(User::class);
        $this->thread->subscribe($user->id);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);

        $user = create(User::class);
        $this->thread->subscribe($user->id);

        Notification::assertNotSentTo($user, ThreadWasUpdated::class);
    }

    /** @test */
    function thread_belongs_to_a_channel()
    {
        $thread = create(Thread::class);

        $this->assertInstanceOf(Channel::class, $thread->channel);
    }

    /** @test */
    function thread_can_be_subscribed_to()
    {
        $this->thread->subscribe($userId = 1);

        $this->assertEquals(
            1,
            $this->thread->subscriptions()->where('user_id', $userId)->count()
        );
    }

    /** @test */
    function thread_can_be_unsubscribed_from()
    {
        $this->thread->subscribe($userId = 1);

        $this->thread->unsubscribe($userId);

        $this->assertCount(0, $this->thread->subscriptions);
    }

    /** @test */
    function check_current_user_is_subscribed_to_it()
    {
        $this->signIn();

        $this->assertFalse($this->thread->isSubscribedTo);

        $this->thread->subscribe();

        $this->assertTrue($this->thread->isSubscribedTo);
    }

    /** @test */
    function thread_can_check_if_authenticated_user_read_all_replies()
    {
        $this->signIn();

        /** @var Thread $thread */
        $thread = create(Thread::class);

        $this->assertTrue($thread->hasUpdatesFor(auth()->user()));

        // Simulate that the user visited the thread
        auth()->user()->readThread($thread);

        $this->assertFalse($thread->hasUpdatesFor(auth()->user()));
    }
}
