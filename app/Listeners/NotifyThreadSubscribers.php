<?php

namespace App\Listeners;

use App\Events\ThreadHasNewReply;

class NotifyThreadSubscribers
{
    public function handle(ThreadHasNewReply $event): void
    {
        // Prepare notifications for all subscribed users
        $event->thread->notifySubscribers($event->reply);
    }
}
