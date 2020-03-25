<?php

namespace Tests\Feature;

use App\Reply;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guest_can_not_favorite_anything()
    {
        $this->post('/replies/1/favorites')
            ->assertRedirect(route('login'));
    }

    /** @test */
    function authenticated_user_can_favorite_any_reply()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        /** @var Reply $reply */
        $reply = create(Reply::class);

        $this->post('/replies/' . $reply->id . '/favorites');

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    function authenticated_user_can_unfavorite_a_reply()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        /** @var Reply $reply */
        $reply = create(Reply::class);

        $reply->favorite();

        $this->assertCount(1, $reply->favorites);

        $this->delete('/replies/' . $reply->id . '/favorites');

        $this->assertCount(0, $reply->fresh()->favorites);
    }

    /** @test */
    function authenticated_user_may_favorite_a_reply_only_once()
    {
        $this->signIn();

        /** @var Reply $reply */
        $reply = create(Reply::class);

        $this->post('/replies/' . $reply->id . '/favorites');
        $this->post('/replies/' . $reply->id . '/favorites');

        $this->assertCount(1, $reply->favorites);
    }
}
