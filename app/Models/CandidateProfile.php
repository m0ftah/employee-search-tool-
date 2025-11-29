<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'resume_path',
        'education_level',
        'skills',
        'years_of_experience',
        'current_job_title',
        'bio',
    ];

    protected $casts = [
        'skills' => 'array',
        'years_of_experience' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasManyThrough(JobApplication::class, User::class, 'id', 'candidate_id', 'user_id', 'id');
    }

    // Accessors
    public function getResumeUrlAttribute()
    {
        return $this->resume_path ? asset('storage/' . $this->resume_path) : null;
    }

    public function getSkillsListAttribute()
    {
        return $this->skills ? implode(', ', $this->skills) : 'No skills listed';
    }

    public function getProfileCompletenessAttribute()
    {
        $fields = ['phone', 'address', 'resume_path', 'education_level', 'skills', 'years_of_experience', 'current_job_title', 'bio'];
        $completed = 0;

        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $completed++;
            }
        }

        return round(($completed / count($fields)) * 100);
    }

    // Scopes
    #[Scope]
    protected function withSkills($query, array $skills)
    {
        return $query->where(function ($q) use ($skills) {
            foreach ($skills as $skill) {
                $q->orWhereJsonContains('skills', $skill);
            }
        });
    }

    #[Scope]
    protected function withExperience($query, $minYears)
    {
        return $query->where('years_of_experience', '>=', $minYears);
    }

    #[Scope]
    protected function withEducation($query, $educationLevel)
    {
        return $query->where('education_level', $educationLevel);
    }

}
