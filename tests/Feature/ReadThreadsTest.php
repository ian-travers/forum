<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
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
        $this->get(route('threads'))
            ->assertSee($this->thread->title);
    }

    /** @test */
    function user_can_rad_single_thread()
    {
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    /** @test */
    function user_can_filter_threads_according_to_a_channel()
    {
        $this->withoutExceptionHandling();

        /** @var Channel $channel */
        $channel = create(Channel::class);

        /** @var Thread $threadInChannel */
        $threadInChannel = create(Thread::class, ['channel_id' => $channel->id]);
        /** @var Thread $threadNotInChannel */
        $threadNotInChannel = create(Thread::class);

        $this->get(route('channels', $channel))
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    function user_can_filter_threads_by_username()
    {
        $this->signIn(create(User::class, ['name' => 'John']));

        $threadByJonh = create(Thread::class, ['user_id' => auth()->id()]);
        $threadNotByJonh = create(Thread::class);

        $this->get('/threads?by=John')
            ->assertSee($threadByJonh->title)
            ->assertDontSee($threadNotByJonh->title);
    }

    /** @test */
    function user_can_filter_threads_by_popularity()
    {
        $threadWithTwoReplies = create(Thread::class);
        create(Reply::class, ['thread_id' => $threadWithTwoReplies->id], 2);

        $threadWithoutReplies = $this->thread;

        $threadWithThreeReplies = create(Thread::class);
        create(Reply::class, ['thread_id' => $threadWithThreeReplies->id], 3);

        $response = $this->getJson('/threads?popular=1')->json();

        $this->assertEquals([3,2,0], array_column($response['data'], 'replies_count'));
    }

    /** @test */
    function user_can_filter_all_unanswered_threads()
    {
        $thread = create(Thread::class);
        create(Reply::class, ['thread_id' => $thread->id]);

        $response = $this->getJson('/threads?unanswered=1')->json();

        $this->assertCount(1, $response['data']);
    }

    /** @test */
    function user_can_request_all_replies_for_a_given_thread()
    {
        create(Reply::class, ['thread_id' => $this->thread->id], 2);

        $response = $this->getJson($this->thread->path() . '/replies')->json();

        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }

    /** @test */
    function we_record_a_new_visit_each_time_the_thread_is_read()
    {
        /** @var Thread $thread */
        $thread = create(Thread::class);

        $this->assertSame(0, $thread->visits);

        $this->call('get', route('threads.show', [$thread->channel, $thread]));

        $this->assertEquals(1, $thread->fresh()->visits);
    }
}
