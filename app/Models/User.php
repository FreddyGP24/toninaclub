<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'role', 'active', 'password'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'role' => UserRole::class,
            'active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function hotels(): HasMany
    {
        return $this->hasMany(Hotel::class, 'owner_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function isClient(): bool { return $this->role === UserRole::CLIENT; }
    public function isOwner(): bool { return $this->role === UserRole::OWNER; }
    public function isAdmin(): bool { return $this->role === UserRole::ADMIN; }
}
