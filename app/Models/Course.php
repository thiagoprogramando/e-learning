<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Course extends Model {

    protected $table = 'courses';

    protected $fillable = [
        'uuid',
        'created_by',
        'teacher_id',
        'slug',
        'title',
        'description',
        'thumbnail',
        'time',
        'value',
        'duration',
        'is_published'
    ];

    public function teacher() {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function lessons() {
        return $this->belongsToMany(Lesson::class);
    }

    public function invoices() {
        return $this->hasMany(Invoice::class, 'course_id');
    }

    public function hasApprovedInvoiceForAuthenticatedUser() {
        
        $userId = Auth::id();
        if (!$userId) {
            return false;
        }

        return $this->invoices()->where('user_id', $userId)->where('payment_status', 'paid')->exists();
    }

    public function timeLabel () {

        switch ($this->time) {
            case 'monthly':
                return 'Mensal';
            case 'semi-annual':
                return 'Semestral';
            case 'annual':
                return 'Anual';
            case 'lifetime':
                return 'Vitalício';    
            default:
                return '';
        }
    }

    protected static function boot() {
        parent::boot();

        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = static::generateUniqueSlug($course->title);
            }
        });
    }

    private static function generateUniqueSlug($title) {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
