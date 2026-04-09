<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model {
    
    protected $table = 'subjects';

    protected $fillable = [
        'title',
        'description',
    ];

    public function lessons() {
        return $this->hasMany(Lesson::class, 'subject_id');
    }
}
