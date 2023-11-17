<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\Achievements\AchievementsRepositoryInterface;
use App\Repositories\Achievements\BadgesRepositoryInterface;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    private AchievementsRepositoryInterface $achievementsRepository;
    private BadgesRepositoryInterface $badgesRepository;

    public function __construct(AchievementsRepositoryInterface $achievementsRepository, BadgesRepositoryInterface $badgesRepository)
    {
        $this->achievementsRepository = $achievementsRepository;
        $this->badgesRepository = $badgesRepository;

    }

    public function index(User $user)
    {
        // Get the next available achievements for the user
        $nextAvailableAchievements = $this->achievementsRepository->get_next_achievements($user->id);

        // Get the previous achievements for the user
        $previous_achievements  = $this->achievementsRepository->get_previous_achievements($user->id);

        // Get information about the next badge
        $badgeInfo = $this->badgesRepository->next_badge($user->id);

        // Get the current badge of the user
        $currentBadge = $this->badgesRepository->current_badge($user->id);

        // Handle edge cases for current badge
        $currentBadgeTitle = $currentBadge ? $currentBadge->title : 'none';

        // Handle edge cases for next badge
        $nextBadge = $badgeInfo['next_badge'] ?? null;
        $remainingToUnlockNextBadge = $badgeInfo['remaining_to_unlock_next_badge'] ?? 0;

        // Ensure that the remaining achievements to unlock the next badge is not negative
        $remainingToUnlockNextBadge = max($remainingToUnlockNextBadge, 0);

        return response()->json([
            'unlocked_achievements' => $previous_achievements,
            'next_available_achievements' => $nextAvailableAchievements,
            'current_badge' => $currentBadgeTitle,
            'next_badge' => $nextBadge,
            'remaining_to_unlock_next_badge' => $remainingToUnlockNextBadge
        ]);
    }

}
