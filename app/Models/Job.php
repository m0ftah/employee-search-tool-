<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;

    protected $table = 'job_postings';

    protected $fillable = [
        'hr_id',
        'title',
        'description',
        'location',
        'job_type',
        'salary_range',
        'experience_level',
        'category',
        'application_deadline',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'application_deadline' => 'date',
        ];
    }

    /**
     * Get the HR that owns the job.
     */
    public function hr(): BelongsTo
    {
        return $this->belongsTo(HR::class, 'hr_id');
    }

    /**
     * Get the applications for the job.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}

