<?php

namespace App\Repositories\Achievements;

use App\Events\AchievementUnlockedEvent;
use App\Events\BadgeUnlockedEvent;
use App\Models\Achievement;
use App\Models\Comment;
use App\Models\LessonUser;
use App\Models\User;
use App\Models\UserAchievement;
use function Psy\debug;

class AchievementsRepository implements AchievementsRepositoryInterface
{

    /**
     * @param $user_id
     * @param $type
     * @return mixed
     */
    public function get_current_achievement($user_id, $type): mixed
    {
        // Validate $type to be either 'comment' or 'lesson'
        if (!in_array($type, ['comment', 'lesson'])) {
            return false;
        }

        $userAchievementColumn = "achievement_id_{$type}";

        $userAchievement = UserAchievement::where('user_id', $user_id)->first();

        if ($userAchievement) {
            return Achievement::find($userAchievement->$userAchievementColumn);
        }

        return null; // Return null if no user achievement found
    }

    /**
     * @param $user_id
     * @return array|null
     */
    public function get_previous_achievements($user_id): ?array
    {
        $userAchievement = UserAchievement::where('user_id', $user_id)->first();

        if (!$userAchievement) {
            return null; // Return null if no user achievement found
        }

        $commentAchievement = $this->get_current_achievement($user_id, 'comment');
        $lessonAchievement = $this->get_current_achievement($user_id, 'lesson');

        $previousCommentAchievements = $commentAchievement && $commentAchievement->unlocked_at
            ? Achievement::where('type', 'comment')
                ->where('unlocked_at', '<=', $commentAchievement->unlocked_at)
                ->orderBy('unlocked_at', 'desc')
                ->pluck('title')
                ->toArray()
            : []; // Empty array if no comment achievement unlocked yet

        $previousLessonAchievements = $lessonAchievement && $lessonAchievement->unlocked_at
            ? Achievement::where('type', 'lesson')
                ->where('unlocked_at', '<=', $lessonAchievement->unlocked_at)
                ->orderBy('unlocked_at', 'desc')
                ->pluck('title')
                ->toArray()
            : []; // Empty array if no lesson achievement unlocked yet

        return [
            'previous_comment_achievements' => $previousCommentAchievements,
            'previous_lesson_achievements' => $previousLessonAchievements,
        ];


        // Merge the two collections into a single array
        //return $previousCommentAchievements->merge($previousLessonAchievements)->all();

    }


    /**
     * @param $user_id
     * @return array|null
     */
    public function get_next_achievements($user_id): ?array
    {

        $userAchievement = UserAchievement::where('user_id', $user_id)->first();

        if (!$userAchievement) {
            return null; // Return null if no user achievement found
        }

        // Determine the unlocked_at for comment and lesson achievements
        $commentAchievementUnlockedAt = $userAchievement->achievement_id_comment
            ? optional(Achievement::find($userAchievement->achievement_id_comment))->unlocked_at
            : null;

        $lessonAchievementUnlockedAt = $userAchievement->achievement_id_lesson
            ? optional(Achievement::find($userAchievement->achievement_id_lesson))->unlocked_at
            : null;

        $nextCommentAchievement = $commentAchievementUnlockedAt !== null
            ? Achievement::where('type', 'comment')->where('unlocked_at', '>', $commentAchievementUnlockedAt)->orderBy('unlocked_at', 'asc')->first()
            : Achievement::where('type', 'comment')->orderBy('unlocked_at', 'asc')->first();

        $nextLessonAchievement = $lessonAchievementUnlockedAt !== null
            ? Achievement::where('type', 'lesson')->where('unlocked_at', '>', $lessonAchievementUnlockedAt)->orderBy('unlocked_at', 'asc')->first()
            : Achievement::where('type', 'lesson')->orderBy('unlocked_at', 'asc')->first();


        return [
            'next_comment_achievement' => optional($nextCommentAchievement)->title,
            'next_lesson_achievement' => optional($nextLessonAchievement)->title,
        ];
    }


    public function add_achievement($user_id, $record_id, $type): void
    {
        $achievement = $this->calculate_achievement($user_id, $type);
        if (!$achievement) {
            return; // Exit early if there's no achievement
        }

        $achievementColumn = "achievement_id_{$type}";
        $currentAchievementId = $achievement->id;

        // Fetch the existing record if it exists
        $existingUserAchievement = UserAchievement::where('user_id', $user_id)->first();

        // Determine if the achievement column value is going to change
        $isAchievementChanging = $existingUserAchievement && $existingUserAchievement->$achievementColumn !== $currentAchievementId;

        // Update or Create the record
        $data = [
            "{$type}_id" => $record_id,
            $achievementColumn => $currentAchievementId,
        ];
        $user_achievement = UserAchievement::updateOrCreate(['user_id' => $user_id], $data);

        $user = User::find($user_achievement->user_id);

        // Handle new record creation
        if ($user_achievement->wasRecentlyCreated) {
            $this->handleNewUserAchievement($user_achievement);
            $achieveName =  $this->get_current_achievement($user->id,$type);
            event(new AchievementUnlockedEvent($achieveName,$user));
        }

        // Increment total achievements if the achievement column value has changed
        if ($isAchievementChanging) {
            $user_achievement->increment('total_achievements');
        }

        $user_achievement->refresh();
        $new_badge = BadgesRepository::new_badge($user_achievement->total_achievements);
        if ($new_badge && $user_achievement->badge_id !== $new_badge->id) {
            $user_achievement->badge_id = $new_badge->id;
            $user_achievement->save();

            event(new BadgeUnlockedEvent($new_badge->title,$user));

            $achieveName =  $this->get_current_achievement($user->id,$type);
            event(new AchievementUnlockedEvent($achieveName,$user));

        }
    }


    /**
     * @param $user_achievement
     * @return void
     */
    protected function handleNewUserAchievement($user_achievement): void
    {
        $badge = BadgesRepository::first_badge();
        $user_achievement->badge_id = $badge->id;
        $user_achievement->total_achievements = 1;
        $user_achievement->save();
        $user = User::find($user_achievement->user_id);
        event(new BadgeUnlockedEvent($badge->title,$user));


    }


    /**
     * @param $user_id
     * @param $type
     * @return mixed
     */
    protected function calculate_achievement($user_id, $type): mixed
    {
        // Validate $type to be either 'comment' or 'lesson'
        if (!in_array($type, ['comment', 'lesson'])) {
            return false;
        }

        $countMethod = $type == 'comment' ? 'countComments' : 'countLessonsWatched';
        $count = $this->$countMethod($user_id);

        if ($count > 0) {
            return Achievement::where('type', $type)
                ->where('unlocked_at', '<=', $count)
                ->orderBy('unlocked_at', 'desc')
                ->first();
        }

        return false;
    }

    /**
     * @param $user_id
     * @return mixed
     */
    protected function countComments($user_id): mixed
    {
        return Comment::where('user_id', $user_id)->count();
    }

    /**
     * @param $user_id
     * @return mixed
     */
    protected function countLessonsWatched($user_id): mixed
    {
        return LessonUser::where('watched', 1)->where('user_id', $user_id)->count();
    }


}
