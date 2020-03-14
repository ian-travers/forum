<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_has_an_owner()
    {
        /** @var Reply $reply */
        $reply = create(Reply::class);

        $this->assertInstanceOf(User::class, $reply->owner);
    }

    /** @test */
    function it_knows_if_it_was_just_published()
    {
        /** @var Reply $reply */
        $reply = create(Reply::class);

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subDay();

        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    function it_can_detect_all_mentioned_users_in_the_body()
    {
        /** @var Reply $reply */
        $reply = new Reply([
            'body' => '@Anna wants to talk with @Jim',
        ]);

        $this->assertEquals(['Anna', 'Jim'], $reply->mentionedUsers());
    }

    /** @test */
    function it_wraps_mentioned_username_in_the_body_within_anchor_tags()
    {
        /** @var Reply $reply */
        $reply = new Reply([
            'body' => 'Hello @Anna. You need to talk with @Jim-Jo.',
        ]);

        $this->assertEquals(
            'Hello <a href="/profiles/Anna">@Anna</a>. You need to talk with <a href="/profiles/Jim-Jo">@Jim-Jo</a>.',
            $reply->body
        );

    }
}
