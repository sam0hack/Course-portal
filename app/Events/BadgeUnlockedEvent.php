<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BadgeUnlockedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $badgeName;
    public $user;

    public function __construct($badgeName, User $user)
    {

        $this->badgeName = $badgeName;
        $this->user = $user;
    }

}
