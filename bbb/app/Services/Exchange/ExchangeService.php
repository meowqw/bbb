<?php

namespace App\Services\Exchange;

use App\Models\PriceHistory\PriceHistory;
use App\Services\Trend\TrendLogic\DTO\TrendLogicDTO;

class ExchangeService
{
    /**
     * Получаем текущая цена пары + комиссия биржы
     *
     * @param TrendLogicDTO $dto
     * @return float|int
     */
    private static function getCurrentPairPriceWithCommission(TrendLogicDTO $dto): float|int
    {
        $currentPrice = $dto->getCurrentPairPrice();
        $bybitCommission = $dto->getBybitCommission();
        return $currentPrice + ($currentPrice / 100 * $bybitCommission);
    }

    /**
     * Получить разницу между стартовой и текущй ценой в процентах
     *
     * @param TrendLogicDTO $dto
     * @return float|int
     */
    public static function getDifferenceBetweenStartAndCurrent(TrendLogicDTO $dto): float|int
    {
        // стартовая цена в ордере
        /** @var PriceHistory $firstPriceHistory */
        $firstPriceHistory = PriceHistory::query()->where('order_id', $dto->getOrderId())->first();
        $startPairPrice = $firstPriceHistory->getAmount();

        $currentPairPriceWithCommission = self::getCurrentPairPriceWithCommission($dto);

        // разница между стартовой и текущей + комиссия
        return abs(($startPairPrice - $currentPairPriceWithCommission) / $startPairPrice * 100);
    }
}
