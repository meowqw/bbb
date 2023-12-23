<?php

namespace App\Services\Setting;

use App\Models\Setting\Setting;

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
}
