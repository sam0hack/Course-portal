<?php

namespace App\Traits;

trait AchievementsList
{
    /**
     * @return array[]
     */
    public static function getAchievementsList(): array
    {
        return [
            ['title' => 'First Lesson Watched', 'type' => 'lesson', 'unlocked_at' => 0],
            ['title' => '5 Lessons Watched', 'type' => 'lesson', 'unlocked_at' => 5],
            ['title' => '10 Lessons Watched', 'type' => 'lesson', 'unlocked_at' => 10],
            ['title' => '25 Lessons Watched', 'type' => 'lesson', 'unlocked_at' => 25],
            ['title' => '50 Lessons Watched', 'type' => 'lesson', 'unlocked_at' => 50],
            ['title' => 'First Comment Written', 'type' => 'comment', 'unlocked_at' => 0],
            ['title' => '3 Comments Written', 'type' => 'comment', 'unlocked_at' => 3],
            ['title' => '5 Comments Written', 'type' => 'comment', 'unlocked_at' => 5],
            ['title' => '10 Comments Written', 'type' => 'comment', 'unlocked_at' => 10],
            ['title' => '20 Comments Written', 'type' => 'comment', 'unlocked_at' => 20],
        ];
    }
}
