<?php

namespace App\Services\Bybit;

use App\Models\Order\Order;
use App\Models\PriceHistory\PriceHistory;
use App\Services\Setting\SettingService;
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
        $settings = SettingService::getSettings();

        // Проверка наличия ордера со статусом open
        $order = Order::query()->where('status', Order::STATUS_OPEN)->first();
        // получение текущей цены пары
        $currentPairPrice = BybitService::getCurrentPrice($settings->getPair());
        // получение минимальной цены покупки
        $minOrderAmt = BybitService::getMinOrderAmt($settings->getPair());
        // получить баланс quoted
        $quotedCoinBalance = BybitService::getWalletBalance($settings->getQuotedCoin());
        $baseCoinBalance = BybitService::getWalletBalance($settings->getBaseCoin());

        Log::info('Текущая цена пары: ' . $currentPairPrice);

        if (is_null($order)) {
            // создание ордера
            $order = (new Order())->setStatus(Order::STATUS_OPEN)->setPair($settings->getPair());
            Log::info('Ордер создан');
        }

        if ($quotedCoinBalance == 0) {
            Log::error('Баланс' . $settings->getQuotedCoin() . ' = 0, создание ордера не представляется возможным');
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
            ->setBybitCommission($settings->getBybitCommission())
            ->setOrderAmount($settings->getOrderAmount())
            ->setBuyPercent($settings->getBuyPercent())
            ->setMinOrderAmt($minOrderAmt)
            ->setSellPercent($settings->getSellPercent())
            ->setQuotedBalance($quotedCoinBalance)
            ->setBaseBalance($baseCoinBalance)
            ->setPair($settings->getPair())
            ->setOrder($order)
            ->setCurrentPairPrice($currentPairPrice);

        $trendLogic = TrendLogicFactory::getTrendLogic($trend);
        $trendLogic->execute($trendLogicDTO);
    }
}
