<?php

namespace App\Notifications;

use App\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class YouAreMentioned extends Notification
{
    use Queueable;

    private $thread;
    private $reply;

    /**
     * Create a new notification instance.
     *
     * @param Reply $reply
     */
    public function __construct($reply)
    {
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'author' => $this->reply->owner->name,
            'action' => 'mentioned you in',
            'thread' => $this->reply->thread->title,
            'link' => $this->reply->path(),
            'at' => $this->reply->created_at->format('Y-m-d H:i')
        ];
    }
}
