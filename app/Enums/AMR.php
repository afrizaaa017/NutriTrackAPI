<?php

namespace App\Enums;

enum AMR : string
{
    case sedentary = 'Sedentary active';
    case lightly = 'Lightly active';
    case moderately = 'Moderately active';
    case highly = 'Highly active';
    case extreme = 'Extremely active';
}
