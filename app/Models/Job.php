<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'location',
        'job_type',
        'experience_level',
        'salary_min',
        'salary_max',
        'company_name',
        'application_deadline',
        'is_active',
        'is_remote',
    ];

    protected $casts = [
        'application_deadline' => 'date',
        'is_active' => 'boolean',
        'is_remote' => 'boolean',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($job) {
            $job->slug = Str::slug($job->title) . '-' . Str::random(6);
        });

        static::updating(function ($job) {
            if ($job->isDirty('title')) {
                $job->slug = Str::slug($job->title) . '-' . Str::random(6);
            }
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function chatRooms()
    {
        return $this->hasMany(ChatRoom::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('application_deadline', '>=', now());
    }

    public function scopeRemote($query)
    {
        return $query->where('is_remote', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('company_name', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['job_type'] ?? null, function ($query, $jobType) {
            $query->where('job_type', $jobType);
        })
            ->when($filters['experience_level'] ?? null, function ($query, $experienceLevel) {
                $query->where('experience_level', $experienceLevel);
            })
            ->when($filters['location'] ?? null, function ($query, $location) {
                $query->where('location', 'like', "%{$location}%");
            })
            ->when($filters['is_remote'] ?? null, function ($query, $isRemote) {
                $query->where('is_remote', (bool)$isRemote);
            });
    }

    // Accessors
    public function getSalaryRangeAttribute()
    {
        if ($this->salary_min && $this->salary_max) {
            return '$' . number_format($this->salary_min) . ' - $' . number_format($this->salary_max);
        } elseif ($this->salary_min) {
            return 'From $' . number_format($this->salary_min);
        } elseif ($this->salary_max) {
            return 'Up to $' . number_format($this->salary_max);
        }

        return 'Negotiable';
    }

    public function getIsExpiredAttribute()
    {
        return $this->application_deadline->isPast();
    }

    public function getApplicationCountAttribute()
    {
        return $this->applications()->count();
    }
}
