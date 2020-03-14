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
     * @param  ThreadReceiveNewReply  $event
     * @return void
     */
    public function handle(ThreadReceiveNewReply $event)
    {
        // Find any mentioned users (@username) in the reply's body, and notify them
        preg_match_all('/@([^\W+]+)/', $event->reply->body, $matches);

        $names = $matches[1];

        foreach ($names as $name) {
            $user = User::whereName($name)->first();
            if ($user) {
                $user->notify(new YouAreMentioned($event->reply));
            }
        }
    }
}
