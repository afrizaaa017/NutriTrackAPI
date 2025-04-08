<?php

namespace App\Enums;

enum UserGoal: string
{
    case GainLittle = 'Gain a little weight';
    case GainALot = 'Gain a lot of weight';
    case LoseLittle = 'Lose a little weight';
    case LoseALot = 'Lose a lot of weight';
    case Maintain = 'Maintain weight';
}
