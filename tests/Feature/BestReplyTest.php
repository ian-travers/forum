<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_thread_creator_may_mark_any_reply_as_the_best()
    {
        $this->signIn();

        /** @var Thread $thread */
        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        /** @var Reply[] $replies */
        $replies = create(Reply::class, ['thread_id' => $thread->id], 2);

        $this->assertFalse($replies[1]->isBest());

        $this->postJson(route('best-reply.store', [$replies[1]]));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    /** @test */
    function only_creator_may_mark_a_reply_as_best()
    {
        $this->signIn();

        /** @var Thread $thread */
        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $replies = create(Reply::class, ['thread_id' => $thread->id], 2);

        $this->signIn(create(User::class));

        $this->postJson(route('best-reply.store', [$replies[1]]))
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertFalse($replies[1]->fresh()->isBest());

    }
}
