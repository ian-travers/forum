<?php

namespace Tests\Feature;

use App\Thread;
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

    /** @test */
    function profiles_displays_all_threads_created_by_associated_user()
    {
        $this->withoutExceptionHandling();
        /** @var User $user */
        $user = create(User::class);

        /** @var Thread $thread */
        $thread = create(Thread::class, ['user_id' => $user->id]);

        $this->get('/profiles/' . $user->name)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
