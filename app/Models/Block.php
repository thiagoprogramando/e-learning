<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Block extends Model {
    
    use SoftDeletes;

    protected $table = 'lesson_blocks';

    protected $fillable = [
        'lesson_id', 
        'title',
        'type', 
        'content', 
        'order'
    ];

    public function labelType () {
        switch ($this->type) {
            case 'text':
                return 'Texto';
            case 'video':
                return 'Vídeo';
            case 'image':
                return 'Imagem';
            case 'audio':
                return 'Áudio';
            case 'embed':
                return 'Embed';
            default:
                return 'Inválido';
        }
    }

    public function lesson() {
        return $this->belongsTo(Lesson::class);
    }
}
