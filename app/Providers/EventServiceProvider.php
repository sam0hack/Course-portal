<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \App\Events\CommentWritten::class => [\App\Listeners\CheckAchievementForComment::class],
        \App\Events\LessonWatched::class => [\App\Listeners\CheckAchievementForLesson::class],

        \App\Events\AchievementUnlockedEvent::class => [\App\Listeners\NotifyAchievementUnlocked::class],
        \App\Events\BadgeUnlockedEvent::class => [\App\Listeners\NotifyBadgeUnlocked::class]


    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
