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
        config(['scout.driver' => 'algolia']);

        $search = 'find me';

        create(Thread::class, [], 2);
        create(Thread::class, ['body' => "Thread with the {$search} term"], 2);

        do {
            sleep(.5);

            $results = $this->getJson("/threads/search?q={$search}")->json()['data'];
        } while(empty($results));


        $this->assertCount(2, $results);

        Thread::latest()->take(4)->unsearchable();
    }
}
