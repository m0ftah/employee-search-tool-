<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'candidate_id',
        'cover_letter',
        'status',
        'feedback',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($application) {
            $application->applied_at = now();
        });
    }

    // Relationships
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }

    public function candidateProfile()
    {
        return $this->hasOneThrough(CandidateProfile::class, User::class, 'id', 'user_id', 'candidate_id', 'id');
    }

    public function chatRoom()
    {
        return $this->hasOne(ChatRoom::class, 'job_id', 'job_id')
            ->where('candidate_id', $this->candidate_id);
    }

    // Scopes
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Methods
    public function createChatRoom()
    {
        return ChatRoom::firstOrCreate([
            'job_id' => $this->job_id,
            'candidate_id' => $this->candidate_id,
        ], [
            'hr_id' => $this->job->user_id,
            'name' => "Chat: {$this->job->title} - {$this->candidate->name}",
        ]);
    }

    public function updateStatus($status, $feedback = null)
    {
        $this->update([
            'status' => $status,
            'feedback' => $feedback
        ]);

        return $this;
    }

    // Accessors
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'submitted' => 'gray',
            'under_review' => 'blue',
            'shortlisted' => 'green',
            'interview' => 'purple',
            'rejected' => 'red',
            'accepted' => 'emerald',
            default => 'gray'
        };
    }

    public function getIsActiveAttribute()
    {
        return in_array($this->status, ['submitted', 'under_review', 'shortlisted', 'interview']);
    }
}
