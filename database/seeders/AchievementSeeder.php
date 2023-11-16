<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Traits\AchievementsList;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    use AchievementsList;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievementsList = self::getAchievementsList();
        Achievement::factory()->count(count($achievementsList))->create();
    }
}
