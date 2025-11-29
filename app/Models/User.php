<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function candidateProfile()
    {
        return $this->hasOne(CandidateProfile::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'candidate_id');
    }

    public function hrChatRooms()
    {
        return $this->hasMany(ChatRoom::class, 'hr_id');
    }

    public function candidateChatRooms()
    {
        return $this->hasMany(ChatRoom::class, 'candidate_id');
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isHR()
    {
        return $this->role === 'hr';
    }

    public function isCandidate()
    {
        return $this->role === 'candidate';
    }

    public function getChatRoomsAttribute()
    {
        return ChatRoom::where('hr_id', $this->id)
            ->orWhere('candidate_id', $this->id)
            ->where('is_active', true)
            ->get();
    }
}
