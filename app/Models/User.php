<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'provider',
        'provider_id',
        'google_id',
        'linkedin_id',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // العلاقات
    public function cvs(): HasMany
    {
        return $this->hasMany(Cv::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // المحولات (Mutators & Accessors)
    public function setPhoneAttribute($value)
    {
        // Remove encryption to fix Filament form display
        $this->attributes['phone'] = $value;
    }

    public function getPhoneAttribute($value)
    {
        // Remove decryption to fix Filament form display
        return $value;
    }

    // النطاقات (Scopes)
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithSocialProvider($query)
    {
        return $query->whereNotNull('provider');
    }

    // طرق مساعدة
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function hasValidCv(int $cvId): bool
    {
        return $this->cvs()->where('id', $cvId)->where('is_paid', true)->exists();
    }

    public function getTotalPaidCvs(): int
    {
        return $this->cvs()->where('is_paid', true)->count();
    }

    public function getTotalSpent(): float
    {
        return $this->payments()->where('status', 'success')->sum('amount');
    }

    // Filament access control
    public function canAccessPanel(Panel $panel): bool
    {
        // User panel: allow any authenticated user
        if ($panel->getId() === 'user') {
            return auth()->check();
        }

        // Admin panel: restrict
        if ($panel->getId() === 'admin') {
            // First check if user has admin role
            if (method_exists($this, 'hasRole') && $this->hasRole('admin')) {
                return true;
            }

            // Check configured admin emails
            $emails = config('admin.emails', []);
            if (empty($emails)) {
                $emails = ['admin@cvbuilder.com'];
            }

            if (in_array(strtolower($this->email), array_map('strtolower', $emails))) {
                return true;
            }

            // Only allow other users in local if they have admin role (already checked above)
            // Remove the blanket allow_any_auth_in_local permission
            return false;
        }

        // Default deny for unknown panels
        return false;
    }
}