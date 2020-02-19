<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
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
    function guest_may_not_add_replies()
    {
        $this->withoutExceptionHandling();

        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post($this->thread->path() . '/replies', []);
    }

    /** @test */
    function authenticated_user_may_participate_in_forum_test()
    {
        $this->withoutExceptionHandling();

        $this->signIn(create(User::class));

        $reply = make(Reply::class);

        $this->post($this->thread->path() . '/replies', $reply->toArray());

        $this->get($this->thread->path())
            ->assertSee($reply->body);
    }
}
