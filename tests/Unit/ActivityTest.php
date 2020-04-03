<?php

namespace Tests\Unit;

use App\Activity;
use App\Reply;
use App\Thread;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_record_activity_when_a_thread_is_created()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        /** @var Thread $thread */
        $thread = create(Thread::class);

        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => Thread::class,
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    function it_record_activity_when_a_reply_is_created()
    {
        $this->signIn();

        create(Reply::class);

        $this->assertEquals(2, Activity::count());
    }

    /** @test */
    function it_fetches_a_feed_for_any_user()
    {
        $this->signIn();

        /** @var User $user */
        $user = auth()->user();

        create(Thread::class, ['user_id' => $user->id], 2);

        $user->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);

        $feed = Activity::feed($user);

        $this->assertTrue($feed->keys()->contains(Carbon::now()->format('Y-m-d')));
        $this->assertTrue($feed->keys()->contains(Carbon::now()->subWeek()->format('Y-m-d')));
    }
}
