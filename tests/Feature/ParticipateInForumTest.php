<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
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

        /** @var Reply $reply */
        $reply = make(Reply::class);

        $this->post($this->thread->path() . '/replies', $reply->toArray());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $this->thread->fresh()->replies_count);
    }

    /** @test */
    function reply_requires_a_body()
    {
        $this->signIn();

        $reply = make(Reply::class, ['body' => null]);

        $this->post($this->thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function unauthorized_users_can_not_delete_replies()
    {
        /** @var Reply $reply */
        $reply = create(Reply::class);

        $this->delete('/replies/' . $reply->id)
            ->assertRedirect('/login');

        $this->signIn();

        $this
            ->delete('/replies/' . $reply->id)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    function authorized_users_can_delete_replies()
    {
        $this->signIn();

        /** @var Reply $reply */
        $reply = create(Reply::class, ['user_id' => auth()->id()]);

        $this->delete('/replies/' . $reply->id)
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    /** @test */
    function unauthorized_users_can_not_update_replies()
    {
        /** @var Reply $reply */
        $reply = create(Reply::class);

        $this->patch('/replies/' . $reply->id)
            ->assertRedirect('/login');

        $this->signIn();

        $this
            ->patch('/replies/' . $reply->id)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    function authorized_users_can_update_replies()
    {
        $this->signIn();

        /** @var Reply $reply */
        $reply = create(Reply::class, ['user_id' => auth()->id()]);

        $updatedReply = 'Body has been changed';
        $this->patch('/replies/' . $reply->id, ['body' => $updatedReply]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updatedReply]);
    }
}
