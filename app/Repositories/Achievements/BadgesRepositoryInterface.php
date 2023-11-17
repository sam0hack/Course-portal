<?php

namespace App\Repositories\Achievements;

interface BadgesRepositoryInterface
{
    /**
     * @param $user_id
     * @return null
     */
    public function current_badge($user_id);

    /**
     * @param $user_id
     * @return int|mixed
     */
    public function next_badge($user_id): mixed;

    /**
     * @return mixed
     */
    public static function first_badge(): mixed;

    /**
     * @param $total_achievements
     * @return mixed
     */
    public static function new_badge($total_achievements): mixed;
}
