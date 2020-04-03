<?php

namespace Tests\Feature\Threads;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

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
}
