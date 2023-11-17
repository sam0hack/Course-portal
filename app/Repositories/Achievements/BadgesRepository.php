<?php

namespace App\Repositories\Achievements;

use App\Models\Badge;
use App\Models\UserAchievement;

class BadgesRepository implements BadgesRepositoryInterface
{

    /**
     * @param $user_id
     * @return null
     */
    public function current_badge($user_id)
    {
        $badgeId = UserAchievement::where('user_id', $user_id)->value('badge_id');

        if ($badgeId) {
            return Badge::where('id', $badgeId)->first();
        }

        return null;
    }


    /**
     * @param $user_id
     * @return int|mixed
     */
    public function next_badge($user_id): mixed
    {
        $currentBadge = $this->current_badge($user_id);

        // Handle case where user has no current badge
        if (!$currentBadge) {
           return 0;
        }

        $currentBadgeUnlockedAt = $currentBadge->unlocked_at ?? 0;

        // Get the next badge based on the current badge's unlocked_at value
        $upcomingBadge = Badge::where('unlocked_at', '>', $currentBadgeUnlockedAt)
            ->orderBy('unlocked_at', 'asc')
            ->first(['unlocked_at','title']);

        // Handle case where there's no next badge (user has the highest badge)
        if (!$upcomingBadge) {
            return 0;
        }

        // Get the total achievements of the user
        $totalAchievements = UserAchievement::where('user_id', $user_id)
            ->sum('total_achievements');

        // Calculate the remaining achievements needed for the next badge
        $remainingForNextBadge = $upcomingBadge->unlocked_at - $totalAchievements;

        $rem =  max($remainingForNextBadge, 0); // Ensures a non-negative result

        return ['next_badge'=>$upcomingBadge->title,'remaining_to_unlock_next_badge'=>$rem];

    }


    /**
     * @return mixed
     */
    public static function first_badge(): mixed
    {
        return Badge::orderBy('unlocked_at', 'asc')->first();
    }

    /**
     * @param $total_achievements
     * @return mixed
     */
    public static function new_badge($total_achievements): mixed
    {

        return Badge::where('unlocked_at', $total_achievements)->first();

    }

}
