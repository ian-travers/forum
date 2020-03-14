<?php

namespace App\Listeners;

use App\Events\ThreadReceiveNewReply;
use App\Notifications\YouAreMentioned;
use App\User;

class NotifyMentionedUsers
{
    /**
     * Handle the event.
     *
     * @param ThreadReceiveNewReply $event
     * @return void
     */
    public function handle($event)
    {
        User::whereIn('name', $event->reply->mentionedUsers())
            ->get()
            ->each(function (User $user) use ($event) {
                $user->notify(new YouAreMentioned($event->reply));
            });
    }
}
