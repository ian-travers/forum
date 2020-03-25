<?php

namespace Tests\Feature;

use App\Activity;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;

class DeleteThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function unauthorized_users_may_not_delete_threads()
    {
        /** @var Thread $thread */
        $thread = create(Thread::class);

        $this->delete($thread->path())
            ->assertRedirect(route('login'));

        /** @var User $user */
        $user = create(User::class);
        $user->markEmailAsVerified();
        $this->signIn($user);

        $this->delete(route('thread.destroy', [$thread->channel, $thread]))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    function authorized_users_can_delete_threads()
    {
        /** @var User $user */
        $user = create(User::class);
        $user->markEmailAsVerified();
        $this->signIn($user);

        /** @var Thread $thread */
        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        /** @var Reply $reply */
        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $this->json('delete', route('thread.destroy', [$thread->channel, $thread]))
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertDatabaseMissing('activities', [
            'subject_id' => $thread->id,
            'subject_type' => get_class($thread)
        ]);

        $this->assertDatabaseMissing('activities', [
            'subject_id' => $reply->id,
            'subject_type' => get_class($reply)
        ]);

        $this->assertEquals(0, Activity::count());
    }
}
