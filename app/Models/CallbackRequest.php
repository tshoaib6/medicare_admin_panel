<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallbackRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'call_date',
        'call_time',
        'time_zone',
        'status',
        'notes',
    ];

    protected $casts = [
        'call_date' => 'date',
        'call_time' => 'datetime:H:i',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
