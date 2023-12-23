<?php

namespace App\Services\Bybit;

use Lin\Bybit\BybitV5;

class ApiClientService
{
    /**
     * Получить клинет для работы с api bybit
     *
     * @return BybitV5
     */
    public static function getClient(): BybitV5
    {
        if (env('BYBIT_TEST')) {
            $host = env('BYBIT_API_TEST_URL');
            $key = env('BYBIT_API_TEST_KEY');
            $secret = env('BYBIT_API_TEST_SECRET');
        } else {
            $host = env('BYBIT_API_URL');
            $key = env('BYBIT_API_KEY');
            $secret = env('BYBIT_API_SECRET');
        }

        $bybit = new BybitV5($key, $secret, $host);

        $bybit->setOptions([
            'timeout' => 10,
            'headers' => [
                'X-BAPI-RECV-WINDOW' => '6000',
            ]
        ]);

        return $bybit;
    }
}
