<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Consume extends Model
{
    use HasFactory;

    protected $table = 'consumes';
    public $incrementing = false; 
    protected $primaryKey = ['email', 'food_id']; 
    protected $keyType = ['string', 'integer'];

    protected $fillable = [
        'email',  
        'food_id',
        'meal_time', 
        'portion',
        'total_sugar',
        'total_calories',
        'total_fat',
        'total_carbs',
        'total_protein',
        'consumed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public function food()
    {
        return $this->belongsTo(Food::class, 'food_id', 'food_id');
    }
}
