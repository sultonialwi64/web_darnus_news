<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements \Filament\Models\Contracts\FilamentUser
{
    protected static function booted()
    {
        static::created(function ($user) {
            // Otomatis buat profil journalist untuk user baru (kecuali sudah ada)
            if ($user->role === 'journalist') {
                \App\Models\Journalist::create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'position' => 'Reporter',
                ]);
            }
        });
    }

    public function journalist()
    {
        return $this->hasOne(\App\Models\Journalist::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return true; // Allow all users (since we only have the seeded admin) to access the panel
    }
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
}
