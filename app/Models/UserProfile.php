<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'user_profiles';
    protected $primaryKey = 'email';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'weight',
        'birthday',
        'height',
        'goal',
        'AMR',
        'image',
        'gender',
        'points',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}



