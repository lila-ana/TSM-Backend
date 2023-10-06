<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


use Illuminate\Notifications\Messages\DatabaseMessage;


class TaskCreatedNotification extends Notification
{
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'A new task has been created',
            // Add any additional data you want to store in the database
        ];
    }
}
