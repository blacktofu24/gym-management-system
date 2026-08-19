<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SlotAvailableNotification extends Notification
{
    use Queueable;

    protected $timeSlot;
    protected $waitlistId;

    public function __construct($timeSlot, $waitlistId)
    {
        $this->timeSlot = $timeSlot;
        $this->waitlistId = $waitlistId;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // We will store this notification in the database
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Waitlist Spot Available!',
            'message' => 'A spot opened up on ' . $this->timeSlot->date->format('M d') . ' at ' . $this->timeSlot->start_time->format('g:i A') . '. Click here to claim it!',
            'url' => route('member.waitlist.confirm', $this->waitlistId)
        ];
    }
}