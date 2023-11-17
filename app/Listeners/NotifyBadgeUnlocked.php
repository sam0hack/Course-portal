<?php

namespace App\Listeners;

use App\Events\BadgeUnlockedEvent;
use App\Mail\NewBadge;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NotifyBadgeUnlocked
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BadgeUnlockedEvent $event): void
    {
        $user = $event->user;
        $badgeName = $event->badgeName;

        Mail::to($user->email)->send(new NewBadge($user, $badgeName));

    }
}
