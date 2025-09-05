<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'provider',
        'provider_id',
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
        $this->attributes['phone'] = $value ? encrypt($value) : null;
    }

    public function getPhoneAttribute($value)
    {
        return $value ? decrypt($value) : null;
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
}