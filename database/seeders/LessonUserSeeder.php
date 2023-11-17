<?php

namespace Database\Seeders;


use App\Models\LessonUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lessons = LessonUser::factory()
            ->count(30)
            ->create();
    }
}
