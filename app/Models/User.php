<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\{Fillable, Hidden};
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'phone',
    'country',
    'role_id',
    'membership',
    'origin',
    'global_discount',
    'avatar_path',
    'last_login_at',
])]
#[Hidden([
    'password',
    'remember_token',
])]
class User extends Authenticatable
{
    use Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'global_discount' => 'integer',
        ];
    }

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function hasPermission(string $permission): bool
    {
        return $this->role && is_array($this->role->permissions) && in_array($permission, $this->role->permissions);
    }

    public function isAdmin(): bool
    {
        return $this->role && $this->role->name === 'admin';
    }

    public function grades(): HasMany
    {
        return $this->hasMany(StudentGrade::class);
    }

    public function writtenAnswers(): HasMany
    {
        return $this->hasMany(WrittenAnswer::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(PendingPayment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function systemNotifications(): HasMany
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar_path 
            ? asset('storage/' . $this->avatar_path) 
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0d2145&color=fff';
    }
}
