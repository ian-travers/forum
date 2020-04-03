<?php

namespace Tests\Feature;

use App\Activity;
use App\Channel;
use App\Reply;
use App\Rules\Recaptcha;
use App\Thread;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        app()->singleton(Recaptcha::class, function () {
            $m = \Mockery::mock(Recaptcha::class);

            $m->shouldReceive('passes')->andReturn(true);

            return $m;
        });
    }

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
        $user = factory(User::class)->states('unverified')->create();
        $this->signIn($user);

        $thread = make(Thread::class);

        $this->json('post', route('threads'), $thread->toArray())
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'You must first confirm your email.');
    }

    /** @test */
    function authenticated_user_can_create_new_thread()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        /** @var Thread $thread */
        $thread = make(Thread::class);

        $response = $this->post(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token']);

        $this->get($response->headers->get('Location'))
            ->assertStatus(Response::HTTP_OK)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    function thread_requires_a_title_and_body_to_be_updated()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        /** @var Thread $thread */
        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->expectException(ValidationException::class);
        $this->patch($thread->path(), [
            'title' => 'New title',
        ]);

        $this->expectException(ValidationException::class);
        $this->patch($thread->path(), [
            'body' => 'New body',
        ]);
    }

    /** @test */
    function unauthorized_users_may_not_update_threads()
    {
        $this->signIn();

        /** @var Thread $thread */
        $thread = create(Thread::class, ['user_id' => create(User::class)->id]);

        $this->patch($thread->path(), [
            'title' => 'New title',
            'body' => 'New body'
        ])->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    function thread_can_be_updated_by_its_creator()
    {
        $this->signIn();

        /** @var Thread $thread */
        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'New title',
            'body' => 'New body'
        ]);

        tap($thread->fresh(), function ($thread) {
            $this->assertEquals('New title', $thread->title);
            $this->assertEquals('New body', $thread->body);
        });
    }

    /** @test */
    function authorized_users_can_delete_threads()
    {
        $this->signIn();

        /** @var Thread $thread */
        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        /** @var Reply $reply */
        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $response = $this->json('delete', $thread->path());

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertEquals(0, Activity::count());
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
    function thread_requires_recaptcha_verification()
    {
        unset(app()[Recaptcha::class]);

        $this->publishThread(['g-recaptcha-response' => 'test'])
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

        $this->signIn();

        /** @var Thread $thread */
        $thread = create(Thread::class, ['title' => 'Foo Title']);

        $this->assertEquals($thread->fresh()->slug, 'foo-title');

        $thread = $this->postJson(route('threads', $thread->toArray() + ['g-recaptcha-response' => 'test']))->json();

        $this->assertEquals("foo-title-{$thread['id']}", $thread['slug']);
        $this->assertTrue(Thread::whereSlug("foo-title-{$thread['id']}")->exists());
    }

    /** @test */
    function thread_with_title_that_ands_in_a_number_should_generate_proper_slug()
    {
        $this->signIn();

        /** @var Thread $thread */
        $thread = create(Thread::class, ['title' => 'Title 22']);

        $thread = $this->postJson(route('threads', $thread->toArray() + ['g-recaptcha-response' => 'test']))->json();

        $this->assertEquals("title-22-{$thread['id']}", $thread['slug']);
        $this->assertTrue(Thread::whereSlug("title-22-{$thread['id']}")->exists());
    }

    protected function publishThread($overrides = [])
    {
        $this->signIn();

        $thread = make(Thread::class, $overrides);

        return $this->json('post', route('threads'), $thread->toArray());
    }
}
