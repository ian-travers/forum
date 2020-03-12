<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function mentioned_user_in_a_reply_are_notified()
    {
        $john = create(User::class, ['name' => 'JohnDoe']);

        $this->signIn($john);

        /** @var User $anna */
        $anna = create(User::class, ['name' => 'AnnaFaris']);

        /** @var Thread $thread */
        $thread = create(Thread::class);

        /** @var Reply $reply */
        $reply = make(Reply::class, [
            'body' => '@AnnaFaris look at this!! And @Frank.',
            'thread_id' => $thread->id
        ]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $anna->fresh()->notifications);
    }
}
