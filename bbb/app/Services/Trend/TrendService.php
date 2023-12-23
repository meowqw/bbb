<?php

namespace App\Services\Trend;

use App\Models\PriceHistory\PriceHistory;
use App\Models\Setting\Setting;

class TrendService
{
    /**
     * Получить текущий тренд пары
     * Определяется путем сравенения друг с другом послдних значений истории цен в кол-ве PRICE_TREND_LIMIT_CODE
     *
     * @param int $orderId
     * @return TrendLogic
     */
    public static function getTrend(int $orderId): TrendLogic
    {
        /** @var Setting $trendLimit */
        $trendLimit = Setting::query()
            ->where('code', Setting::PRICE_TREND_LIMIT_CODE)
            ->first();

        $priceHistory = PriceHistory::query()->where('order_id', $orderId)->get();

        if (count($priceHistory) <= (float)$trendLimit->getValue()) {
            return TrendLogic::Empty;
        }

        $prices = array_slice($priceHistory->pluck('amount')->toArray(), -(float)$trendLimit->getValue());

        $trend = TrendLogic::Neutral;
        $trendResults = [];

        for ($i = 0; $i < $trendLimit->getValue() - 1; $i++) {
            if ($prices[$i] < $prices[$i + 1]) {
                $trendResults[] = TrendLogic::Increase;
            } elseif ($prices[$i] > $prices[$i + 1]) {
                $trendResults[] = TrendLogic::Decrease;
            }
        }

        if (in_array(TrendLogic::Increase, $trendResults) && !in_array(TrendLogic::Decrease, $trendResults)) {
            $trend = TrendLogic::Increase;
        }

        if (!in_array(TrendLogic::Increase, $trendResults) && in_array(TrendLogic::Decrease, $trendResults)) {
            $trend = TrendLogic::Decrease;
        }

        return $trend;
    }
}
