<?php

namespace App\Services\Trend;

enum TrendLogic: string
{
    case Increase = 'increase';
    case Decrease = 'decrease';
    case Neutral = 'neutral';
    case Empty = 'empty';
}
