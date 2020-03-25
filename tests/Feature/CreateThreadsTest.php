<?php

namespace Tests\Feature;

use App\Channel;
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

        $this->post(route('threads'), []);
    }

    /** @test */
    function guest_can_not_see_create_thread_page()
    {
        $this->get(route('threads.create'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    function new_user_must_first_confirm_their_email_before_creating_threads()
    {
        $this->signIn();

        $thread = make(Thread::class);

        $this->json('post', route('threads'), $thread->toArray())
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'You must first confirm your email.');
    }

    /** @test */
    function authenticated_user_can_create_new_thread()
    {
        $this->withoutExceptionHandling();

        /** @var User $user */
        $user = create(User::class);
        $user->markEmailAsVerified();

        $this->signIn($user);

        /** @var Thread $thread */
        $thread = make(Thread::class);

        $response = $this->post(route('threads'), $thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertStatus(Response::HTTP_OK)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    function thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    function thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    function thread_requires_a_valid_channel()
    {
        factory(Channel::class, 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->publishThread(['channel_id' => 999999])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    function thread_requires_a_unique_slug()
    {
        $this->withoutExceptionHandling();

        /** @var User $user */
        $user = create(User::class);
        $user->markEmailAsVerified();

        $this->signIn($user);

        /** @var Thread $thread */
        $thread = create(Thread::class, ['title' => 'Foo Title', 'slug' => 'foo-title']);

        $this->assertEquals($thread->fresh()->slug, 'foo-title');

        $this->post(route('threads', $thread->toArray()));

        $this->assertTrue(Thread::whereSlug('foo-title-2')->exists());
    }

    protected function publishThread($overrides = [])
    {
        /** @var User $user */
        $user = create(User::class);
        $user->markEmailAsVerified();

        $this->signIn($user);

        $thread = make(Thread::class, $overrides);

        return $this->json('post', route('threads'), $thread->toArray());
    }
}
