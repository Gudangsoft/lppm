<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InternalGrant extends Model
{
    protected $fillable = [
        'contract_number', 'submission_id', 'contract_date', 'start_date', 'end_date',
        'approved_budget', 'contract_file', 'status', 'notes', 'approved_by', 'approved_at'
    ];

    protected $casts = [
        'contract_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_budget' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(InternalGrantSubmission::class, 'submission_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function disbursements(): HasMany
    {
        return $this->hasMany(InternalGrantDisbursement::class, 'grant_id');
    }

    public function progressReports(): HasMany
    {
        return $this->hasMany(InternalGrantProgressReport::class, 'grant_id');
    }

    public function outputs(): HasMany
    {
        return $this->hasMany(InternalGrantOutput::class, 'grant_id');
    }

    public function finalReport(): HasOne
    {
        return $this->hasOne(InternalGrantFinalReport::class, 'grant_id');
    }

    public function getTotalDisbursedAttribute(): float
    {
        return $this->disbursements()->where('status', 'completed')->sum('amount');
    }

    public function getTotalSpentAttribute(): float
    {
        return $this->progressReports()->sum('budget_spent') 
            + ($this->finalReport?->total_budget_spent ?? 0);
    }

    public function getProgressPercentageAttribute(): int
    {
        $latestReport = $this->progressReports()->latest()->first();
        return $latestReport?->progress_percentage ?? 0;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'completed' => 'bg-blue-100 text-blue-800',
            'terminated' => 'bg-red-100 text-red-800',
            'extended' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active' => 'Aktif',
            'completed' => 'Selesai',
            'terminated' => 'Dihentikan',
            'extended' => 'Diperpanjang',
            default => $this->status,
        };
    }

    public function getDisbursementPercentageAttribute(): float
    {
        if (!$this->approved_budget || $this->approved_budget == 0) {
            return 0;
        }
        return round(($this->total_disbursed / $this->approved_budget) * 100, 2);
    }

    public static function generateContractNumber($year = null): string
    {
        $year = $year ?? date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return sprintf('KONTRAK/HI/%s/%04d', $year, $count);
    }
}
