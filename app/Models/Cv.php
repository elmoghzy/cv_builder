<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Cv extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'template_id',
        'accent_color',
        'content',
        'title',
    'status',
        'is_paid',
        'paid_at',
        'pdf_path',
        'download_count',
    ];

    protected $casts = [
        'content' => 'array',
    'status' => 'string',
        'is_paid' => 'boolean',
        'paid_at' => 'datetime',
        'download_count' => 'integer',
    ];

    // العلاقات
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    // الخصائص المحسوبة (Accessors)
    public function getPdfUrlAttribute(): ?string
    {
        return $this->pdf_path && Storage::exists($this->pdf_path) 
            ? Storage::url($this->pdf_path) 
            : null;
    }

    public function getFullNameAttribute(): string
    {
        $personalInfo = $this->content['personal_info'] ?? [];
        $firstName = $personalInfo['first_name'] ?? '';
        $lastName = $personalInfo['last_name'] ?? '';
        return trim($firstName . ' ' . $lastName) ?: 'Unnamed CV';
    }

    public function getProgressPercentageAttribute(): int
    {
        $requiredSections = ['personal_info', 'experience', 'education', 'skills'];
        $completedSections = 0;

        foreach ($requiredSections as $section) {
            if (!empty($this->content[$section])) {
                $completedSections++;
            }
        }

        return intval(($completedSections / count($requiredSections)) * 100);
    }

    // النطاقات (Scopes)
    public function scopePaid($query)
    {
        return $query->where('is_paid', true);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('is_paid', false);
    }

    // طرق مساعدة
    public function canDownload(): bool
    {
        return $this->is_paid && $this->pdf_path && Storage::exists($this->pdf_path);
    }

    public function isComplete(): bool
    {
        return $this->progress_percentage >= 80; // 80% أو أكثر
    }

    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
    }
}
