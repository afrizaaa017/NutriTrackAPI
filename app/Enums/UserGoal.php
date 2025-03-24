<?php

namespace App\Enums;

enum UserGoal: string
{
    case GainLittle = 'gain a little weight';
    case GainALot = 'gain a lot of weight';
    case LoseLittle = 'lose a little weight';
    case LoseALot = 'lose a lot of weight';
    case Maintain = 'maintain';
}
