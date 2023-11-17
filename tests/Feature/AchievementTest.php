<?php

namespace Tests\Feature;

use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\LessonUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AchievementTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function test_successful_response_from_achievements_endpoint()
    {

        $response = $this->get("/users/{$this->user->id}/achievements");
        $response->assertStatus(200);
    }

    public function test_basic_json_structure_from_achievements_endpoint()
    {
        $response = $this->get("/users/{$this->user->id}/achievements");

        $expectedJson = [
            "unlocked_achievements" => null,
            "next_available_achievements" => null,
            "current_badge" => "none",
            "next_badge" => "",
            "remaining_to_unlock_next_badge" => 0,
        ];

        $response->assertStatus(200)
            ->assertJson($expectedJson);
    }

    public function test_unlocked_badge_response_after_comment_written()
    {


        //Event::fake(); //Event::fake(), this prevents the actual event listeners from running.
        // If the test relies on the side effects of these listeners the test will fail because those side effects are not occurring

        $comment = Comment::factory()->for($this->user)->create();

        event(new CommentWritten($comment));

        $response = $this->get("/users/{$this->user->id}/achievements");

        $expectedJson = [
            "unlocked_achievements" => [
                "previous_comment_achievements" => [],
                "previous_lesson_achievements" => []
            ],
            "next_available_achievements" => [
                "next_comment_achievement" => "3 Comments Written",
                "next_lesson_achievement" => "First Lesson Watched"
            ],
            "current_badge" => "Beginner",
            "next_badge" => "Intermediate",
            "remaining_to_unlock_next_badge" => 3
        ];

        $response->assertStatus(200)
            ->assertJson($expectedJson);
    }

    public function test_write_three_comments()
    {


        for ($i = 0; $i <= 3; $i++) {

            $comment = Comment::factory()->for($this->user)->create();

            event(new CommentWritten($comment));
        }

        $response = $this->get("/users/{$this->user->id}/achievements");

        $expectedJson = [
            "unlocked_achievements" => [
                "previous_comment_achievements" => [
                    0 => "3 Comments Written",
                    1 => "First Comment Written",
                ],
                "previous_lesson_achievements" => [],
            ],
            "next_available_achievements" => [
                "next_comment_achievement" => "5 Comments Written",
                "next_lesson_achievement" => "First Lesson Watched",
            ],
            "current_badge" => "Beginner",
            "next_badge" => "Intermediate",
            "remaining_to_unlock_next_badge" => 2
        ];

        $response->assertStatus(200)
            ->assertJson($expectedJson);


    }

    public function test_unlock_ten_comment_write_achievement()
    {


        for ($i = 0; $i <= 10; $i++) {

            $comment = Comment::factory()->for($this->user)->create();

            event(new CommentWritten($comment));
        }

        $response = $this->get("/users/{$this->user->id}/achievements");


        $expectedJson = [
            "unlocked_achievements" => [
                "previous_comment_achievements" => [
                    0 => "10 Comments Written",
                    1 => "5 Comments Written",
                    2 => "3 Comments Written",
                    3 => "First Comment Written",
                ],
                "previous_lesson_achievements" => [],
            ],
            "next_available_achievements" => [
                "next_comment_achievement" => "20 Comments Written",
                "next_lesson_achievement" => "First Lesson Watched",
            ],
            "current_badge" => "Intermediate",
            "next_badge" => "Advanced",
            "remaining_to_unlock_next_badge" => 4
        ];

        $response->assertStatus(200)
            ->assertJson($expectedJson);
    }

    public function test_unlock_ten_lessons_watched_achievement()
    {

        Lesson::factory()->count(10)->create();
        $lessons = Lesson::get();

        foreach ($lessons as $lesson) {
            LessonUser::factory()->for($lesson)->for($this->user)->create();
            event(new LessonWatched($lesson, $this->user));
        }

        $response = $this->get("/users/{$this->user->id}/achievements");


        //Once we watched 10 lessons The next achievement should be "25 Lessons Watched"
        $expectedJson = [
            "unlocked_achievements" => [
                "previous_comment_achievements" => [],
                "previous_lesson_achievements" => [
                    0 => "10 Lessons Watched",
                    1 => "5 Lessons Watched",
                    2 => "First Lesson Watched",
                ],
            ],
            "next_available_achievements" => [
                "next_comment_achievement" => "First Comment Written",
                "next_lesson_achievement" => "25 Lessons Watched",
            ],
            "current_badge" => "Beginner",
            "next_badge" => "Intermediate",
            "remaining_to_unlock_next_badge" => 1
        ];


        $response->assertStatus(200)
            ->assertJson($expectedJson);

    }


    public function test_unlock_advanced_badge()
    {

        Lesson::factory()->count(10)->create();
        $lessons = Lesson::get();

        foreach ($lessons as $lesson) {
            LessonUser::factory()->for($lesson)->for($this->user)->create();
            event(new LessonWatched($lesson, $this->user));
        }

        for ($i = 0; $i <= 20; $i++) {

            $comment = Comment::factory()->for($this->user)->create();

            event(new CommentWritten($comment));
        }

        $response = $this->get("/users/{$this->user->id}/achievements");

        $expectedJson = [
            "unlocked_achievements" => [
                "previous_comment_achievements" => [
                    0 => "20 Comments Written",
                    1 => "10 Comments Written",
                    2 => "5 Comments Written",
                    3 => "3 Comments Written",
                    4 => "First Comment Written",
                ],
                "previous_lesson_achievements" => [
                    0 => "10 Lessons Watched",
                    1 => "5 Lessons Watched",
                    2 => "First Lesson Watched",
                ],
            ],
            "next_available_achievements" => [
                "next_comment_achievement" => null,
                "next_lesson_achievement" => "25 Lessons Watched",
            ],
            "current_badge" => "Advanced",
            "next_badge" => "Master",
            "remaining_to_unlock_next_badge" => 2
        ];

        $response->assertStatus(200)
            ->assertJson($expectedJson);

    }


    public function test_100_comments_100_lessons_watched_unlock_master_badge()
    {

        Lesson::factory()->count(100)->create();
        $lessons = Lesson::get();

        foreach ($lessons as $lesson) {
            LessonUser::factory()->for($lesson)->for($this->user)->create();
            event(new LessonWatched($lesson, $this->user));
        }

        for ($i = 0; $i <= 100; $i++) {

            $comment = Comment::factory()->for($this->user)->create();

            event(new CommentWritten($comment));
        }

        $response = $this->get("/users/{$this->user->id}/achievements");

        $expectedJson = [
            "unlocked_achievements" => [
                "previous_comment_achievements" => [
                    0 => "20 Comments Written",
                    1 => "10 Comments Written",
                    2 => "5 Comments Written",
                    3 => "3 Comments Written",
                    4 => "First Comment Written",
                ],
                "previous_lesson_achievements" => [
                    0 => "50 Lessons Watched",
                    1 => "25 Lessons Watched",
                    2 => "10 Lessons Watched",
                    3 => "5 Lessons Watched",
                    4 => "First Lesson Watched",
                ],
            ],
            "next_available_achievements" => [
                "next_comment_achievement" => null,
                "next_lesson_achievement" => null,
            ],
            "current_badge" => "Master",
            "next_badge" => "",
            "remaining_to_unlock_next_badge" => 0
        ];

        $response->assertStatus(200)
            ->assertJson($expectedJson);

    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Achievement::factory()->count(10)->create();
        Badge::factory()->count(4)->create();

    }

}
