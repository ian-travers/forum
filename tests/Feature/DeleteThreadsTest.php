<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;

class DeleteThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guest_can_not_delete_threads()
    {
        /** @var Thread $thread */
        $thread = create(Thread::class);

        $this->delete($thread->path())
            ->assertRedirect('/login');
    }

    /** @test */
    function thread_can_be_deleted()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        /** @var Thread $thread */
        $thread = create(Thread::class);
        /** @var Reply $reply */
        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $this->json('delete', $thread->path())
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    /** @test */
    function threads_may_only_be_deleted_by_those_who_have_permission()
    {
        // TODO: later or combine with other test
    }
}
