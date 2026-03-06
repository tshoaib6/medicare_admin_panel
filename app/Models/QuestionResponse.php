<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionnaire_response_id',
        'question_id',
        'answer_value',
        'answer_text',
    ];

    protected $casts = [
        'answer_value' => 'array',
    ];

    public function questionnaireResponse()
    {
        return $this->belongsTo(QuestionnaireResponse::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    // Get formatted answer for display
    public function getFormattedAnswerAttribute()
    {
        if ($this->question->type === 'text' || $this->question->type === 'textarea') {
            return $this->answer_text;
        }

        if (is_array($this->answer_value)) {
            // For multiple choice questions
            $options = $this->question->options()->whereIn('id', $this->answer_value)->pluck('label');
            return $options->implode(', ');
        }

        // For single choice questions
        if ($this->answer_value) {
            $option = $this->question->options()->where('id', $this->answer_value)->first();
            return $option ? $option->label : $this->answer_value;
        }

        return null;
    }
}
