<?php

namespace App\Notifications;

use App\Reply;
use App\Thread;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ThreadWasUpdated extends Notification
{
    use Queueable;

    private $thread;
    private $reply;

    /**
     * ThreadWasUpdated constructor.
     *
     * @param Thread $thread
     * @param Reply $reply
     */
    public function __construct($thread, $reply)
    {
        $this->thread = $thread;
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'author' => $this->reply->owner->name,
            'action' => 'reply to',
            'thread' => $this->thread->title,
            'link' => $this->reply->path(),
            'at' => $this->reply->created_at->format('Y-m-d H:i')
        ];
    }
}
