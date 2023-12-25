<?php

namespace App\Services\Bybit;

use App\Models\Order\Order;
use App\Models\PriceHistory\PriceHistory;
use App\Models\Request\Request;
use App\Models\Setting\Setting;
use App\Services\Exchange\ExchangeService;
use App\Services\Setting\SettingService;
use App\Services\Trend\TrendLogic;
use App\Services\Trend\TrendLogic\DecreaseLogic;
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
        if (SettingService::getServiceStatus() == Setting::STATUS_SERVICE_OFF) {
            return;
        }

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
        Log::info('Ордер №' . $order->getId() . ' имеет тренд ' . strtoupper($trend->value));

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

        $pricePercentDifference = ExchangeService::getDifferenceBetweenStartAndCurrent($trendLogicDTO);

        Log::info('Ордер №' . $trendLogicDTO->getOrderId() . ' ' .
            'Текущий процент: ' . $pricePercentDifference . '/' .
            'Ожидаемый: ' . $trendLogicDTO->getBuyPercent());

        $trendLogic = TrendLogicFactory::getTrendLogic($trend);
        $trendLogic->execute($trendLogicDTO);

        $order = self::longLivedOrder($order, $settings->getLongLivedTime());

        if ($order->getStatus() == Order::STATUS_LONG_LIVED) {
            self::longLivedLogic($trendLogicDTO);
        }
    }

    /**
     * Проверка ордера на долгожителя
     *
     * @param Order $order
     * @param int $longLivedTime
     * @return Order
     */
    private static function LongLivedOrder(Order $order, int $longLivedTime): Order
    {
        if (now()->diffInMinutes($order->getCreateAt()) < $longLivedTime) {
            return $order;
        }

        $requestExists = Request::query()->where('order_id', $order->getId())->exists();
        if (!$requestExists) {
            $order->setStatus(Order::STATUS_LONG_LIVED);
            $order->save();
            Log::info('Ордер №' . $order->getId() . ' закрыт как долгожитель !');
        }

        return $order;
    }

    /**
     * Поведение при закрытии ордера как долгожителя
     *
     * @param TrendLogicDTO $dto
     * @return void
     */
    private static function longLivedLogic(TrendLogicDTO $dto): void
    {
        Log::info('Отработана логика долгожителя, создан ордер и произведена ЗАКУПКА');
        $order = (new Order())->setStatus(Order::STATUS_OPEN)->setPair($dto->getPair());
        $order->save();
        $dto->setOrder($order)->setOrderId($order->getId());
        DecreaseLogic::buy($dto, $dto->getOrderAmount());
    }
}
