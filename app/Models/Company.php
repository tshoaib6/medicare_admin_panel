<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_url',
        'rating',
        'phone',
        'specialties',
    ];

    protected $casts = [
        'specialties' => 'array',
        'rating' => 'decimal:2',
    ];

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }

    public function callbackRequests()
    {
        return $this->hasMany(CallbackRequest::class);
    }
}
