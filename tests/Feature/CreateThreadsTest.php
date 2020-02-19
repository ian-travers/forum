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
    function guest_can_not_see_create_thread_page()
    {
        $this->get('/threads/create')
            ->assertRedirect('/login');
    }

    /** @test */
    function authenticated_user_can_create_new_thread()
    {
        $this->withoutExceptionHandling();

        $this->signIn(create(User::class));

        /** @var Thread $thread */
        $thread = create(Thread::class);

        $this->post('/threads', $thread->toArray());

        $this->get($thread->path())
            ->assertStatus(Response::HTTP_OK)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
