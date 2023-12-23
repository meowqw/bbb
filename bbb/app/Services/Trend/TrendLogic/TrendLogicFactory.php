<?php

namespace App\Services\Trend\TrendLogic;

use App\Services\Trend\TrendLogic;
use Exception;

class TrendLogicFactory
{
    /**
     * Получить логику поведения в зависимости от тренда
     *
     * @param TrendLogic $trend
     * @return TrendLogicInterface
     * @throws Exception
     */
    public static function getTrendLogic(TrendLogic $trend): TrendLogicInterface
    {
        return match ($trend) {
            TrendLogic::Increase => new IncreaseLogic(),
            TrendLogic::Decrease => new DecreaseLogic(),
            TrendLogic::Neutral =>new NeutralLogic(),
            default => throw new Exception('Для данного тренда нет логики поведения')
        };
    }
}
