<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAchievement extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','achievement_id_comment','comment_id','achievement_id_lesson','lesson_id','badge_id','total_achievements'];
}
