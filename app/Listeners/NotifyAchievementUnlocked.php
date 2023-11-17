<?php

namespace App\Listeners;

use App\Events\AchievementUnlockedEvent;
use App\Mail\NewAchievement;
use Illuminate\Support\Facades\Mail;

class NotifyAchievementUnlocked
{

    public function handle(AchievementUnlockedEvent $event)
    {
        // Logic to send a notification to the user

        $user = $event->user;
        $achievementName = $event->achievementName;


        // Notification::send($user, new AchievementUnlockedNotification($achievementName));
        Mail::to($user->email)->send(new NewAchievement($user, $achievementName->title));

    }
}
