<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {
    
    protected $table = 'tickets';
    
    protected $fillable = [
        'uuid',
        'created_by',
        'teacher_id',
        'course_id',
        'lesson_id',
        'comment',
        'response',
        'status'
    ];

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function teacher() {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function course() {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function lesson() {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }
    
}
