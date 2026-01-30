<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Namu\WireChat\Traits\Chatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, Chatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the HR profile associated with the user.
     */
    public function hr(): HasOne
    {
        return $this->hasOne(HR::class);
    }

    /**
     * Get the candidate profile associated with the user.
     */
    public function candidate(): HasOne
    {
        return $this->hasOne(Candidate::class);
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }

    /**
     * Check if user is HR.
     */
    public function isHR(): bool
    {
        return $this->type === 'hr';
    }

    /**
     * Check if user is candidate.
     */
    public function isCandidate(): bool
    {
        return $this->type === 'candidate';
    }

    /**
     * Override Wirechat methods to allow only Candidates and HR to chat
     */
    public function canCreateChats(): bool
    {
        return $this->isCandidate() || $this->isHR();
    }

    public function canCreateGroups(): bool
    {
        return $this->isHR(); // Only HR can create groups
    }

    /**
     * Override Wirechat display name to show user name
     */
    public function getDisplayNameAttribute(): ?string
    {
        return $this->name ?? 'user';
    }

    /**
     * Override Wirechat cover URL (avatar)
     */
    public function getCoverUrlAttribute(): ?string
    {
        if ($this->isHR() && $this->hr && $this->hr->company_logo) {
            return asset('storage/' . $this->hr->company_logo);
        }
        return null;
    }

    /**
     * Customize searchChatables to only return Candidates for HR and HR for Candidates
     */
    public function searchChatables(string $query): ?\Illuminate\Database\Eloquent\Collection
    {
        $searchableFields = \Namu\WireChat\Facades\WireChat::searchableFields();
        $userModel = app(config('wirechat.user_model', \App\Models\User::class));

        if (blank($query) || !$userModel) {
            return null;
        }

        $columnCache = [];

        $queryBuilder = $userModel::where(function ($q) use ($searchableFields, $query, &$columnCache) {
            $table = $q->getModel()->getTable();
            foreach ($searchableFields as $field) {
                if (!isset($columnCache[$table])) {
                    $columnCache[$table] = \Illuminate\Support\Facades\Schema::getColumnListing($table);
                }
                if (in_array($field, $columnCache[$table])) {
                    $q->orWhere($field, 'LIKE', '%' . $query . '%');
                }
            }
        });

        // Filter: Candidates can only search for HR, HR can only search for Candidates
        if ($this->isCandidate()) {
            $queryBuilder->where('type', 'hr');
        } elseif ($this->isHR()) {
            $queryBuilder->where('type', 'candidate');
        } else {
            // Admins or other types cannot search
            return collect();
        }

        return $queryBuilder->limit(20)->get();
    }
}
