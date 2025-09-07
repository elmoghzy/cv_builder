<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'content',
    'styling',
        'preview_image',
        'is_active',
        'is_premium',
        'sort_order',
    ];

    protected $casts = [
        'content' => 'array',
    'styling' => 'array',
        'is_active' => 'boolean',
        'is_premium' => 'boolean',
        'sort_order' => 'integer',
    ];

    // العلاقات
    public function cvs(): HasMany
    {
        return $this->hasMany(Cv::class);
    }

    // النطاقات (Scopes)
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_premium', false);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // طرق مساعدة
    public function getPreviewUrl(): string
    {
        return $this->preview_image 
            ? asset('storage/' . $this->preview_image)
            : asset('images/template-preview-default.png');
    }

    public function getTotalUsage(): int
    {
        return $this->cvs()->count();
    }
}
