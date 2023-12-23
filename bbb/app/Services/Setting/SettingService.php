<?php

namespace App\Services\Setting;

use App\Models\Setting\Setting;
use App\Services\Setting\DTO\SettingsDTO;

class SettingService
{
    /**
     * Получить текущую пару
     *
     * @return string
     */
    public static function getPair(): string
    {
        /** @var Setting $baseCoin
         * @var Setting $quotedCoin
         */
        $baseCoin = Setting::query()->where('code', Setting::BASE_COIN)->first();
        $quotedCoin = Setting::query()->where('code', Setting::QUOTED_COIN)->first();
        return $baseCoin->getValue() . $quotedCoin->getValue();
    }

    /**
     * Получить настройки
     *
     * @return SettingsDTO
     */
    public static function getSettings(): SettingsDTO
    {
        $settings = Setting::query()->get();
        $baseCoin = $settings->where('code', Setting::BASE_COIN)->first()->getValue();
        $quotedCoin = $settings->where('code', Setting::QUOTED_COIN)->first()->getValue();
        $pair = $baseCoin . $quotedCoin;
        $buyPercent = (float)$settings->where('code', Setting::BUY_PERCENT_DIFFERENCE_CODE)->first()->getValue();
        $sellPercent = (float)$settings->where('code', Setting::SELL_PERCENT_DIFFERENCE_CODE)->first()->getValue();
        $bybitCommission = (float)$settings->where('code', Setting::BYBIT_COMMISSION_CODE)->first()->getValue();
        $orderAmount = (float)$settings->where('code', Setting::ORDER_AMOUNT_CODE)->first()->getValue();

        return (new SettingsDTO())
            ->setPair($pair)
            ->setBaseCoin($baseCoin)
            ->setQuotedCoin($quotedCoin)
            ->setBuyPercent($buyPercent)
            ->setSellPercent($sellPercent)
            ->setBybitCommission($bybitCommission)
            ->setOrderAmount($orderAmount);
    }

    /**
     * Получить текущий статус сервиса (on/off)
     *
     * @return string
     */
    public static function getServiceStatus(): string
    {
        /** @var Setting $serviceStatus */
        $serviceStatus = Setting::query()->where('code', Setting::SERVICE_STATUS_CODE)->first();
        return $serviceStatus->getValue();
    }
}
