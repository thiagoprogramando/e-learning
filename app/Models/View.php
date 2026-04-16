<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class View extends Model {
    
    protected $fillable = [
        'user_id',
        'course_id',
        'lesson_id',
        'block_id',
        'completed'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function lesson() {
        return $this->belongsTo(Lesson::class);
    }

    public function block() {
        return $this->belongsTo(Block::class);
    }
}
