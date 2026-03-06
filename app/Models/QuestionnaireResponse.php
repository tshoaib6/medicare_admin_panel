<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'questionnaire_id',
        'status',
        'started_at',
        'completed_at',
        'metadata',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    public function questionResponses()
    {
        return $this->hasMany(QuestionResponse::class);
    }

    // Calculate completion percentage
    public function getCompletionPercentageAttribute()
    {
        $totalQuestions = $this->questionnaire->questions()->count();
        $answeredQuestions = $this->questionResponses()->count();
        
        return $totalQuestions > 0 ? round(($answeredQuestions / $totalQuestions) * 100) : 0;
    }

    // Get time taken to complete
    public function getTimeTakenAttribute()
    {
        if ($this->started_at && $this->completed_at) {
            return $this->started_at->diffInMinutes($this->completed_at);
        }
        return null;
    }
}
