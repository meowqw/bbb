<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('settings')->insert(
            [
                [
                    'type' => 'main',
                    'name' => 'Размер ордера',
                    'code' => 'order_amount',
                    'value' => '5'
                ],
                [
                    'type' => 'main',
                    'name' => 'Процент на покупку',
                    'code' => 'buy_percent_difference',
                    'value' => '-0.3'
                ],
                [
                    'type' => 'main',
                    'name' => 'Процент на продажу',
                    'code' => 'sell_percent_difference',
                    'value' => '0.4'
                ],
                [
                    'type' => 'main',
                    'name' => 'Лимит позиции в итории на получение тренда',
                    'code' => 'price_trend_limit',
                    'value' => '3'
                ],
                [
                    'type' => 'main',
                    'name' => 'Комиссия bybit',
                    'code' => 'bybit_commission',
                    'value' => '0.1'
                ],
                [
                    'type' => 'main',
                    'name' => 'Базовая валюта пары',
                    'code' => 'base_coin',
                    'value' => 'SOL'
                ],
                [
                    'type' => 'main',
                    'name' => 'Котируемая валюта пары',
                    'code' => 'quoted_coin',
                    'value' => 'USDT'
                ],
                [
                    'type' => 'main',
                    'name' => 'Текущий статус сервиса',
                    'code' => 'service_status',
                    'value' => 'off'
                ],
                [
                    'type' => 'main',
                    'name' => 'Время, которое дается ордеру, чтобы совершить заявку',
                    'code' => 'long_lived_time',
                    'value' => '15'
                ],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->truncate();
    }
};
