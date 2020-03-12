<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function user_can_fetch_most_recent_reply()
    {
        /** @var User $user */
        $user = create(User::class);

        $this->assertNull($user->lastReply);

        /** @var Reply $reply */
        $reply = create(Reply::class, ['user_id' => $user->id]);

        $this->assertEquals($user->fresh()->lastReply->id, $reply->id);
    }
}
