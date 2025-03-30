<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Food extends Model
{
    use HasFactory;

    protected $table = 'foods';
    protected $primaryKey = 'food_id';
    public $incrementing = false;
    protected $keyType = 'integer';
    
    protected $fillable = [
        'food_id', 
        'food_name',
        // 'serving_size'
    ];

    public function food_nutrient():HasMany
    {
        return $this->hasMany(FoodNutrient::class);
    }

    public function user()
    {
        return $this->belongsToMany(User::class)->withPivot('food_id')->withTimestamps();
    }
}
