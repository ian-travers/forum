<?php

namespace Tests\Feature;

use App\Thread;
use App\Trending;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var Trending
     */
    private $trending;

    protected function setUp(): void
    {
        parent::setUp();

        $this->trending = new Trending();

        $this->trending->reset();
    }

    /** @test */
    function it_increments_a_thread_score_each_time_it_is_read()
    {
        $this->assertCount(0, $this->trending->get());

        /** @var Thread $thread */
        $thread = create(Thread::class);

        $this->call('get', $thread->path());

        $trending = $this->trending->get();

        $this->assertCount(1, $trending);
        $this->assertEquals($thread->title, $trending[0]->title);

        $this->trending->reset();
    }
}
