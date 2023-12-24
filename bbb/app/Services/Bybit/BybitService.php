<?php

namespace App\Services\Bybit;

use Illuminate\Support\Facades\Log;

class BybitService
{
    /**
     * Получить кол-во монеток на кошельке
     *
     * @param string $coin
     * @return float
     */
    public static function getWalletBalance(string $coin): float
    {
        $query = [
            'accountType' => 'UNIFIED',
            'coin' => $coin,
        ];

        $client = ApiClientService::getClient();
        $response = $client->account()->getWalletBalance($query);
        return (float)$response['result']['list'][0]['coin'][0]['walletBalance'];

    }

    /**
     * Получить текущую цену пары
     *
     * @param string $pair
     * @return float
     */
    public static function getCurrentPrice(string $pair): float
    {
        $query = [
            'symbol' => $pair,
            'limit' => 1,
            'category' => 'spot',
            'interval' => 1
        ];

        $client = ApiClientService::getClient();
        $response = $client->market()->getKline($query);
        return (float)$response['result']['list'][0][4];
    }

    /**
     * Получить размер минимального ордера
     *
     * @param string $pair
     * @return float
     */
    public static function getMinOrderAmt(string $pair): float
    {
        $query = [
            'symbol' => $pair,
            'status' => 'Trading',
            'category' => 'spot',
        ];

        $client = ApiClientService::getClient();
        $response = $client->market()->getInstrumentsInfo($query);
        return (float)$response['result']['list'][0]['lotSizeFilter']['minOrderAmt'];
    }

    /**
     * Купить монетки
     *
     * @param string $pair
     * @param float $amount
     * @return bool
     */
    public static function buy(string $pair, float $amount): bool
    {
        $query = [
            'symbol' => $pair,
            'side' => 'Buy',
            'category' => 'spot',
            'orderType' => 'Market',
            'qty' => (string)$amount
        ];

        return self::orderCreate($query);
    }

    /**
     * Продать монетки
     *
     * @param string $pair
     * @param float $amount
     * @return bool
     */
    public static function sell(string $pair, float $amount): bool
    {
        $query = [
            'symbol' => $pair,
            'side' => 'Sell',
            'category' => 'spot',
            'orderType' => 'Market',
            'qty' => substr((string) $amount, 0, strpos((string) $amount, '.') + 3)
        ];

        return self::orderCreate($query);
    }

    /**
     * Создание ордера на покупку/продажу
     *
     * @param array $query
     * @return bool
     */
    private static function orderCreate(array $query): bool
    {
        $client = ApiClientService::getClient();
        $response = $client->order()->postCreate($query);
        $retMessage = $response['retMsg'];
        $retCode = $response['retCode'];

        if ($retCode == 0) {
            return true;
        } else {
            Log::error("Buy/Sell error, $retMessage");
            return false;
        }
    }
}
