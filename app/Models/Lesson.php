<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Lesson extends Model {
    
    use SoftDeletes;

    protected $table = 'lessons';

    protected $fillable = [
        'uuid',
        'created_by',
        'teacher_id',
        'subject_id',
        'slug',
        'title',
        'description',
    ];

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function teacher() {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subject() {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function blocks() {
        return $this->hasMany(Block::class, 'lesson_id')->orderBy('order');
    }

    protected static function booted(): void {
        static::creating(function (Lesson $lesson) {
            if (empty($lesson->slug) && !empty($lesson->title)) {
                $lesson->slug = self::generateUniqueSlug($lesson->title);
            }
        });
    }

    public static function generateUniqueSlug(string $title): string {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (self::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
