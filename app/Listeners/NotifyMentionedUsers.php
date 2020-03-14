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
        $mentionedUsers = $event->reply->mentionedUsers();

        foreach ($mentionedUsers as $name) {
            $user = User::whereName($name)->first();
            if ($user) {
                $user->notify(new YouAreMentioned($event->reply));
            }
        }
    }
}
