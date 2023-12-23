<?php

namespace App\Services\Bybit;

use App\Models\Order\Order;
use App\Models\PriceHistory\PriceHistory;
use App\Models\Setting\Setting;
use App\Services\Trend\TrendLogic;
use App\Services\Trend\TrendLogic\DTO\TrendLogicDTO;
use App\Services\Trend\TrendLogic\TrendLogicFactory;
use App\Services\Trend\TrendService;
use Exception;
use Illuminate\Support\Facades\Log;

class BybitOrderService
{
    /**
     * Запуск обработки ордера
     *
     * @return void
     * @throws Exception
     */
    public static function orderProcessing(): void
    {
        // Проверка наличия ордера со статусом open
        $order = Order::query()->where('status', Order::STATUS_OPEN)->first();

        $settings = Setting::query()->get();
        $baseCoin = $settings->where('code', Setting::BASE_COIN)->first()->getValue();
        $quotedCoin = $settings->where('code', Setting::QUOTED_COIN)->first()->getValue();
        $pair = $baseCoin . $quotedCoin;
        $buyPercent = (float)$settings->where('code', Setting::BUY_PERCENT_DIFFERENCE_CODE)->first()->getValue();
        $salePercent = (float)$settings->where('code', Setting::SALE_PERCENT_DIFFERENCE_CODE)->first()->getValue();
        $bybitCommission = (float)$settings->where('code', Setting::BYBIT_COMMISSION_CODE)->first()->getValue();
        $orderAmount = (float)$settings->where('code', Setting::ORDER_AMOUNT_CODE)->first()->getValue();
        // получение текущей цены пары
        $currentPairPrice = BybitService::getCurrentPrice($pair);
        // получение минимальной цены покупки
        $minOrderAmt = BybitService::getMinOrderAmt($pair);
        // получить баланс quoted
        $quotedCoinBalance = BybitService::getWalletBalance($quotedCoin);
        $baseCoinBalance = BybitService::getWalletBalance($baseCoin);

        Log::info('Текущая цена пары: ' . $currentPairPrice);

        if (is_null($order)) {
            // создание ордера
            $order = (new Order())->setStatus(Order::STATUS_OPEN)->setPair($pair);
            Log::info('Ордер создан');
        }

        if ($quotedCoinBalance == 0) {
            Log::error("Баланс $quotedCoin = 0, создание ордера не представляется возможным");
            return;
        }

        $order->save();

        // запись в историю
        (new PriceHistory())->setAmount($currentPairPrice)->setOrderId($order->getId())->save();

        // получение тренда
        $trend = TrendService::getTrend($order->getId());
        Log::info('Ордер №' . $order->getId() . ' на текущий момент имеет тренд ' . $trend->value);

        if ($trend == TrendLogic::Empty) {
            return;
        }

        $trendLogicDTO = (new TrendLogicDTO)
            ->setOrderId($order->getId())
            ->setBybitCommission($bybitCommission)
            ->setOrderAmount($orderAmount)
            ->setBuyPercent($buyPercent)
            ->setMinOrderAmt($minOrderAmt)
            ->setSalePercent($salePercent)
            ->setQuotedBalance($quotedCoinBalance)
            ->setBaseBalance($baseCoinBalance)
            ->setPair($pair)
            ->setOrder($order)
            ->setCurrentPairPrice($currentPairPrice);

        $trendLogic = TrendLogicFactory::getTrendLogic($trend);
        $trendLogic->execute($trendLogicDTO);
    }
}
