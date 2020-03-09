<?php

namespace App\Events;

use App\Reply;
use App\Thread;
use Illuminate\Queue\SerializesModels;

class ThreadHasNewReply
{
    use SerializesModels;

    /**
     * @var Thread
     */
    public $thread;
    /**
     * @var Reply
     */
    public $reply;

    public function __construct(Thread $thread, Reply $reply)
    {
        $this->thread = $thread;
        $this->reply = $reply;
    }
}
