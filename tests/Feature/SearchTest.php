<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_can_search_threads()
    {
        $search = 'find me';

        create(Thread::class, [], 2);
        create(Thread::class, ['body' => "Thread with the {$search} term"], 2);

        $results = $this->getJson("/threads/search?q={$search}")->json();

        $this->assertCount(2, $results['data']);
    }
}
