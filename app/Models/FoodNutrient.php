<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodNutrient extends Model
{
    use HasFactory;

    protected $table = 'food_nutrients';
    protected $primaryKey = 'food_id';
    public $incrementing = false;
    protected $keyType = 'integer';
    

    protected $fillable = [
        'nutrient_name',
        'nutrient_number',
        'unit_name',
    ];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}
