<?php

namespace Tests\Feature;

use App\Models\Lesson;
use App\Models\LessonUser;
use App\Models\User;
use Tests\TestCase;

class LessonTest extends TestCase
{

    public function test_watch_first_lesson()
    {

        $lesson = Lesson::first();

        LessonUser::factory()->for($lesson)->for($this->user)->create();

        // Assert: Check if the lesson is saved in the database
        $this->assertDatabaseHas('lesson_users', [
            'lesson_id' => $lesson->id,
            'user_id' => $this->user->id,
        ]);

    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Lesson::factory()->count(3)->create();

    }


}
