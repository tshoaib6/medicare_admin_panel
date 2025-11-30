<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'content_html',
        'image_url',
        'target_url',
        'target_audience',
        'start_date',
        'end_date',
        'click_count',
        'impression_count',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'click_count' => 'integer',
        'impression_count' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        $now = Carbon::now()->toDateString();
        return $query->where(function ($q) use ($now) {
            $q->whereNull('start_date')
              ->orWhere('start_date', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', $now);
        });
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Accessors
    public function getClickThroughRateAttribute()
    {
        if ($this->impression_count == 0) {
            return 0;
        }
        return round(($this->click_count / $this->impression_count) * 100, 2);
    }

    public function getStatusAttribute()
    {
        if (!$this->is_active) {
            return 'Inactive';
        }
        
        $now = Carbon::now()->toDateString();
        
        if ($this->start_date && $this->start_date > $now) {
            return 'Scheduled';
        }
        
        if ($this->end_date && $this->end_date < $now) {
            return 'Expired';
        }
        
        return 'Active';
    }

    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'Active':
                return 'success';
            case 'Scheduled':
                return 'info';
            case 'Expired':
                return 'warning';
            case 'Inactive':
                return 'danger';
            default:
                return 'secondary';
        }
    }

    // Methods
    public function incrementClicks()
    {
        $this->increment('click_count');
    }

    public function incrementImpressions()
    {
        $this->increment('impression_count');
    }

    public function isCurrentlyActive()
    {
        if (!$this->is_active) {
            return false;
        }
        
        $now = Carbon::now()->toDateString();
        
        if ($this->start_date && $this->start_date > $now) {
            return false;
        }
        
        if ($this->end_date && $this->end_date < $now) {
            return false;
        }
        
        return true;
    }
}
