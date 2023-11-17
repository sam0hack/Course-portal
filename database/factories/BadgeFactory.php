<?php

namespace Database\Factories;

use App\Traits\BadgesList;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BadgeFactory extends Factory
{
    use BadgesList;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return $this->faker->unique()->randomElement(self::getBadgesList());
    }

}
