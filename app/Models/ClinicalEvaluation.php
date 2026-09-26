<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicalEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'screening_id',
        'doctor_id',
        'mfg_score',
        'clinical_notes',
        'action_plan'
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}