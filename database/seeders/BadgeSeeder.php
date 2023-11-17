<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Traits\BadgesList;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    use BadgesList;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Badge::factory()->count(count(self::getBadgesList()))->create();
    }
}
