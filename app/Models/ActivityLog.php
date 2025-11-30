<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'metadata',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
    }

    // Accessors
    public function getActionLabelAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->action));
    }

    public function getActionColorAttribute()
    {
        $colors = [
            'created' => 'success',
            'updated' => 'info', 
            'deleted' => 'danger',
            'viewed' => 'primary',
            'login' => 'success',
            'logout' => 'secondary',
            'failed_login' => 'warning',
            'password_change' => 'info',
            'status_change' => 'warning',
        ];
        
        foreach ($colors as $pattern => $color) {
            if (str_contains($this->action, $pattern)) {
                return $color;
            }
        }
        
        return 'secondary';
    }

    public function getActionIconAttribute()
    {
        $icons = [
            'created' => 'fas fa-plus-circle',
            'updated' => 'fas fa-edit',
            'deleted' => 'fas fa-trash',
            'viewed' => 'fas fa-eye',
            'login' => 'fas fa-sign-in-alt',
            'logout' => 'fas fa-sign-out-alt',
            'failed_login' => 'fas fa-exclamation-triangle',
            'password' => 'fas fa-key',
            'status' => 'fas fa-toggle-on',
            'upload' => 'fas fa-upload',
            'download' => 'fas fa-download',
        ];
        
        foreach ($icons as $pattern => $icon) {
            if (str_contains($this->action, $pattern)) {
                return $icon;
            }
        }
        
        return 'fas fa-circle';
    }

    public function getBrowserAttribute()
    {
        if (!$this->user_agent) return 'Unknown';
        
        $browsers = [
            'Chrome' => 'Chrome',
            'Firefox' => 'Firefox', 
            'Safari' => 'Safari',
            'Edge' => 'Edge',
            'Opera' => 'Opera'
        ];
        
        foreach ($browsers as $pattern => $name) {
            if (str_contains($this->user_agent, $pattern)) {
                return $name;
            }
        }
        
        return 'Other';
    }

    // Static methods for logging
    public static function logActivity($action, $description, $metadata = null, $userId = null)
    {
        return static::create([
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    public static function getPopularActions($limit = 10)
    {
        return static::selectRaw('action, COUNT(*) as count')
                    ->groupBy('action')
                    ->orderByDesc('count')
                    ->limit($limit)
                    ->get();
    }

    public static function getUserActivity($userId, $limit = 20)
    {
        return static::where('user_id', $userId)
                    ->orderByDesc('created_at')
                    ->limit($limit)
                    ->get();
    }
}
