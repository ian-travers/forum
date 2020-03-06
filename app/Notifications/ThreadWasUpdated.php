<?php

namespace App\Notifications;

use App\Reply;
use App\ThreadSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ThreadWasUpdated extends Notification
{
    use Queueable;

    /**
     * @var ThreadSubscription
     */
    private $sub;
    /**
     * @var Reply
     */
    private $reply;

    public function __construct(ThreadSubscription $sub, Reply $reply)
    {
        $this->sub = $sub;
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
            'message' => 'Temporary placeholder'
        ];
    }
}
