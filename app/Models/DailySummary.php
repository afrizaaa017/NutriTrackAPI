<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailySummary extends Model
{
    use HasFactory;

    protected $table = 'daily_summaries';
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'email',
        'date',
        'target_calories',
        'calories_consumed',
        'fat_consumed',
        'sugar_consumed',
        'carbs_consumed',
        'protein_consumed'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
