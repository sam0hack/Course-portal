<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use App\Models\Lesson;
use App\Models\LessonUser;
use App\Repositories\Achievements\AchievementsRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckAchievementForLesson
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
    public function handle(LessonWatched $event): void
    {
        //LessonUser::updateOrCreate(['']);
        $this->achievementsRepository->add_achievement($event->user->id, $event->lesson->id, 'lesson');
    }
}
