<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up(): void {
        Schema::create('views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->foreignId('block_id')->nullable()->constrained('lesson_blocks')->cascadeOnDelete();
            $table->boolean('completed')->default(true);
            $table->timestamps();

            $table->unique(['user_id', 'block_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('views');
    }
};
