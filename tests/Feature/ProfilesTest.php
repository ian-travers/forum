<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function user_has_a_profile()
    {
        /** @var User $user */
        $user = create(User::class);

        $this->get('/profiles/' . $user->name)
            ->assertStatus(Response::HTTP_OK)
            ->assertSee($user->name);
    }
}
