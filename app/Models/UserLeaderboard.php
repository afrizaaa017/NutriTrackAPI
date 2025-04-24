<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLeaderboard extends Model
{
    use HasFactory;

    protected $table = 'user_leaderboard';
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'email',
        'points',
        'streaks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
