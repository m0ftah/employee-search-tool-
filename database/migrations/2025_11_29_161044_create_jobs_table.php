<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique(); // For friendly URLs
            $table->text('description');
            $table->string('location');
            $table->enum('job_type', ['full-time', 'part-time', 'contract', 'internship']);
            $table->enum('experience_level', ['entry-level', 'mid-level', 'senior']);
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->string('company_name'); // Added company name
            $table->date('application_deadline');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_remote')->default(false); // For remote filter
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
