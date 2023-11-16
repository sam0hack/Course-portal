<?php

namespace App\Traits;

trait BadgesList
{

    /**
     * @return int[]
     */
    public static function getBadgesList(): array
    {

        return [
            ['title'=>'Beginner', 'unlocked_at'=> 0],
            ['title'=>'Intermediate', 'unlocked_at' => 4],
            ['title'=>'Advanced', 'unlocked_at'=> 8],
            ['title'=>'Master','unlocked_at' => 10],
            ];

    }


}
