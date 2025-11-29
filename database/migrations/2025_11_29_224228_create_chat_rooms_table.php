<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // Optional: Job title or candidate name
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->foreignId('hr_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Ensure one chat room per job-candidate pair
            $table->unique(['job_id', 'candidate_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_rooms');
    }
};
