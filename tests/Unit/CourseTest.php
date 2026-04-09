<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_generates_slug_automatically_when_creating_course()
    {
        $course = Course::create([
            'uuid' => 'test-uuid',
            'created_by' => 1,
            'teacher_id' => 1,
            'title' => 'Curso de Laravel Avançado',
            'description' => 'Descrição do curso',
            'thumbnail' => 'thumbnail.jpg',
            'time' => 10,
            'value' => 100.00,
            'duration' => '2 horas',
            'is_published' => true,
        ]);

        $this->assertEquals('curso-de-laravel-avancado', $course->slug);
    }

    /** @test */
    public function it_generates_unique_slug_when_title_already_exists()
    {
        Course::create([
            'uuid' => 'test-uuid-1',
            'created_by' => 1,
            'teacher_id' => 1,
            'title' => 'Curso de PHP',
            'description' => 'Descrição 1',
            'thumbnail' => 'thumbnail1.jpg',
            'time' => 5,
            'value' => 50.00,
            'duration' => '1 hora',
            'is_published' => true,
        ]);

        $course2 = Course::create([
            'uuid' => 'test-uuid-2',
            'created_by' => 1,
            'teacher_id' => 1,
            'title' => 'Curso de PHP',
            'description' => 'Descrição 2',
            'thumbnail' => 'thumbnail2.jpg',
            'time' => 5,
            'value' => 50.00,
            'duration' => '1 hora',
            'is_published' => true,
        ]);

        $this->assertEquals('curso-de-php-1', $course2->slug);
    }
}