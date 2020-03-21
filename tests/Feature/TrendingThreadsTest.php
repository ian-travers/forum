<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        Redis::del('trending_threads');
    }

    /** @test */
    function it_increments_a_thread_score_each_time_it_is_read()
    {
        $this->assertCount(0, Redis::zrevrange('trending_threads', 0, -1));

        /** @var Thread $thread */
        $thread = create(Thread::class);

        $this->call('get', $thread->path());

        $this->assertCount(1, Redis::zrevrange('trending_threads', 0, -1));
    }


}
