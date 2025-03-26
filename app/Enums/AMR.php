<?php

namespace App\Enums;

enum AMR : string
{
    case sedentary = 'sedentary active';
    case lightly = 'lightly active';
    case moderately = 'moderately active';
    case highly = 'highly active';
}
