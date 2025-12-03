<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HR extends Model
{
    use HasFactory;

    protected $table = 'hrs';

    protected $fillable = [
        'user_id',
        'company_name',
        'position',
        'phone',
        'location',
        'company_logo',
        'bio',
    ];

    /**
     * Get the user that owns the HR profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the jobs for the HR.
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'hr_id');
    }
}

