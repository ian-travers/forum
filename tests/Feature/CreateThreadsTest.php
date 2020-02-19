<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guest_may_not_create_threads()
    {
        $this->withoutExceptionHandling();

        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post('/threads', []);
    }

    /** @test */
    function authenticated_user_can_create_new_thread()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(factory(User::class)->create());

        /** @var Thread $thread */
        $thread = make(Thread::class);

        $this->post('/threads', $thread->toArray());

        $this->get($thread->path())
            ->assertStatus(Response::HTTP_OK)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
