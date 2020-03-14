<?php

namespace App\Events;

use App\Reply;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ThreadReceiveNewReply
{
    use Dispatchable, SerializesModels;

    public $reply;

    /**
     * ThreadReceiveNewReply constructor.
     * @param Reply $reply
     */
    public function __construct($reply)
    {
        $this->reply = $reply;
    }
}
