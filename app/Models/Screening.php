<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Screening extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gynecological_age',
        'has_severe_acne',
        'has_hirsutism',
        'has_hair_thinning',
        'result_status',
        'explanation_points',
        'recommendation'
    ];

    protected $casts = [
        'explanation_points' => 'array',
        'has_severe_acne' => 'boolean',
        'has_hirsutism' => 'boolean',
        'has_hair_thinning' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clinicalEvaluation()
    {
        return $this->hasOne(ClinicalEvaluation::class);
    }
}