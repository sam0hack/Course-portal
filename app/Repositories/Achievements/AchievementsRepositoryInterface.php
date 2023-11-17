<?php

namespace App\Repositories\Achievements;

interface AchievementsRepositoryInterface
{
    /**
     * @param $user_id
     * @param $type
     * @return false|null
     */
    public function get_current_achievement($user_id, $type): mixed;

    public function get_previous_achievements($user_id): ?array;
    public function get_next_achievements($user_id);

    /**
     * @param $user_id
     * @param $record_id
     * @param $type
     * @return void
     */
    public function add_achievement($user_id, $record_id, $type): void;
}
