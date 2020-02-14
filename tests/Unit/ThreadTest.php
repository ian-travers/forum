<?php

namespace Tests\Unit;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_has_valid_path()
    {
        /** @var Thread $thread */
        $thread = factory('App\Thread')->create();

        $this->assertEquals('/threads/1', $thread->path());
    }
}
