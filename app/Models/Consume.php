<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Consume extends Model
{
    use HasFactory;

    protected $table = 'consumes';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'meal_time', 
        'portion',
        'total_sugar',
        'total_calories',
        'total_fat',
        'total_carbs',
        'total_protein'
    ];
}
