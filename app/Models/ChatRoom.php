<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Namuio\WireChat\Models\Message;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'job_id',
        'hr_id',
        'candidate_id',
        'is_active'
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function hr()
    {
        return $this->belongsTo(User::class, 'hr_id');
    }

    public function candidate()
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'room_id');
    }

    // Get the other participant in the chat
    public function getOtherParticipant(User $user)
    {
        return $user->id === $this->hr_id ? $this->candidate : $this->hr;
    }

    // Check if user is participant in this chat room
    public function isParticipant(User $user)
    {
        return in_array($user->id, [$this->hr_id, $this->candidate_id]);
    }
}
