<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenstrualLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'cycle_length_days',
        'flow_intensity',
        'pain_level'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}