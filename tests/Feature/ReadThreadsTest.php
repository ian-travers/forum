<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Thread
     */
    protected $thread;

    protected function setUp(): void
    {
        parent::setUp();

        $this->thread = create(Thread::class);
    }

    /** @test */
    public function user_can_view_all_threads()
    {
        $this->get('/threads')
            ->assertSee($this->thread->title);
    }

    /** @test */
    function user_can_rad_single_thread()
    {
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    /** @test */
    function user_can_read_replies_that_associated_this_a_thread()
    {
        $reply = create(Reply::class, [
            'thread_id' => $this->thread->id
        ]);

        $this->get($this->thread->path())
            ->assertSee($reply->owner->name)
            ->assertSee($reply->body);
    }
}
