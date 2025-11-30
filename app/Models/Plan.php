<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'slug',
        'title',
        'description',
        'icon',
        'color',
        'benefits',
        'is_available',
    ];

    protected $casts = [
        'benefits' => 'array',
        'is_available' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function questionnaires()
    {
        return $this->hasMany(Questionnaire::class, 'plan_id');
    }
}
