<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use App\Repositories\Achievements\AchievementsRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckAchievementForComment
{
    private AchievementsRepository $achievementsRepository;

    /**
     * Create the event listener.
     */
    public function __construct(AchievementsRepository $achievementsRepository)
    {
        $this->achievementsRepository = $achievementsRepository;
    }

    /**
     * Handle the event.
     */
    public function handle(CommentWritten $event): void
    {
        $this->achievementsRepository->add_achievement($event->comment->user_id, $event->comment->id, 'comment');
    }
}
