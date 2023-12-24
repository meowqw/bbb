<?php

namespace App\Services\Trend\TrendLogic;

use App\Models\Order\Order;
use App\Models\Request\Request;
use App\Services\Bybit\BybitService;
use App\Services\Exchange\ExchangeService;
use App\Services\Trend\TrendLogic\DTO\TrendLogicDTO;
use Illuminate\Support\Facades\Log;

class NeutralLogic implements TrendLogicInterface
{
    /**
     * Поведение при нейтральном тренде
     *
     * @param TrendLogicDTO $dto
     * @return void
     */
    public function execute(TrendLogicDTO $dto): void
    {
        // разница между стартовой и текущей + комиссия
        $pricePercentDifference = ExchangeService::getDifferenceBetweenStartAndCurrent($dto);

        Log::info('Нейтральная: Ордер №' . $dto->getOrderId() .
            ' Текущий процент: ' . $pricePercentDifference . PHP_EOL .
            'Ожидаемый: ' . $dto->getSellPercent());

        // получить инфо есть ли запросы на покупку
        $buyRequestsExists = Request::query()
            ->where('order_id', $dto->getOrderId())
            ->where('type', Request::TYPE_BUY)->exists();

        if (!$buyRequestsExists) {
            Log::info('Нейтральная: Ордер №' . $dto->getOrderId() . ' нет запросов на покупку');
            return;
        }

        if ($pricePercentDifference < $dto->getSellPercent()) {
            Log::info(
                'Нейтральная: Ордер №' . $dto->getOrderId() .
                ' текущий процент разницы (' . $pricePercentDifference .
                ') < ожидаемого процента для продажи (' . $dto->getSellPercent() . ')'
            );
            return;
        }

        $sale = self::sell($dto, $dto->getBaseBalance());

        if ($sale) {
            $dto->getOrder()->setStatus(Order::STATUS_CLOSE)->save();
            Log::info('Нейтральная: Ордер №' . $dto->getOrderId() . ' ПРОДАЖА. Ордер закрыт. Цена: ' . $dto->getBaseBalance());
        } else {
            Log::error('Нейтральная: Ордер №' . $dto->getOrderId() . ' ПРОДАЖА НЕ ПРОШЛА');
        }
    }

    /**
     * Продажа и запись ифнормации о продаже
     *
     * @param TrendLogicDTO $dto
     * @param float $amount
     * @return bool
     */
    private function sell(TrendLogicDTO $dto, float $amount): bool
    {
        // продажа и создание записи о продаже
        $sale = BybitService::sell($dto->getPair(), $amount);
        if ($sale) {
            (new Request())
                ->setOrderId($dto->getOrderId())
                ->setType(Request::TYPE_SELL)
                ->setAmount($amount)->save();

            return true;
        }
        return false;
    }
}
