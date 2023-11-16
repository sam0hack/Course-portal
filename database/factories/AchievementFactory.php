<?php

namespace Database\Factories;

use App\Traits\AchievementsList;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AchievementFactory extends Factory
{
    use AchievementsList;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return $this->faker->unique()->randomElement(self::getAchievementsList());
    }
}
