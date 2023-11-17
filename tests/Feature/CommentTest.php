<?php

namespace Tests\Feature;

use App\Events\CommentWritten;
use App\Models\Comment;
use App\Models\User;
use Tests\TestCase;

class CommentTest extends TestCase
{
    public function test_write_first_comment()
    {


        $comment = Comment::factory()->for($this->user)->create();

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'user_id' => $this->user->id,
        ]);

    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }
}
