<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLES = ['klien', 'developer', 'admin'];

    /** Peran yang boleh dipilih sendiri saat mendaftar */
    public const SELF_ROLES = ['klien', 'developer'];

    protected $fillable = ['nama', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    /** Proyek yang dipasang user ini (sebagai klien) */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    /** Penawaran yang diajukan user ini (sebagai developer) */
    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class, 'developer_id');
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
