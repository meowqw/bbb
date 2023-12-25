<?php

namespace App\Services\Trend\TrendLogic;

use App\Models\Request\Request;
use App\Services\Bybit\BybitService;
use App\Services\Exchange\ExchangeService;
use App\Services\Trend\TrendLogic\DTO\TrendLogicDTO;
use Illuminate\Support\Facades\Log;

class DecreaseLogic implements TrendLogicInterface
{
    /**
     * Поведение при тредне Падение (покупка или выход)
     *
     * @param TrendLogicDTO $dto
     * @return void
     */
    public function execute(TrendLogicDTO $dto): void
    {
        // разница между стартовой и текущей + комиссия
        $pricePercentDifference = ExchangeService::getDifferenceBetweenStartAndCurrent($dto);

        // если ожидаем процент больше чем процент разницы
        if ($dto->getBuyPercent() < $pricePercentDifference) {
            return;
        }

        /** @var Request $lastBuyRequest */
        $lastBuyRequest = Request::query()
            ->where('type', Request::TYPE_BUY)
            ->where('order_id', $dto->getOrderId())->latest()->first();

        // получаем цену покупки
        $buyPrice = $dto->getOrderAmount();
        if (!is_null($lastBuyRequest)) {
            $buyPrice = $lastBuyRequest->getAmount() / 2;
        }

        if ($buyPrice < $dto->getMinOrderAmt()) {
            Log::info('Падение: Ордер №' . $dto->getOrderId() . ' цена закупки < минимальной цены покупки на бирже');
            return;
        }

        if ($dto->getQuotedBalance() < $buyPrice) {
            Log::info('Падение: Ордер №' . $dto->getOrderId() . ' цена закупки > баланса');
            return;
        }

        $buy = self::buy($dto, $buyPrice);
        if ($buy) {
            Log::info('Падение: Ордер №' . $dto->getOrderId() . ' ЗАКУПКА, цена: ' . $buyPrice);
        } else {
            Log::error('Падение: Ордер №' . $dto->getOrderId() . ' ЗАКУПКА НЕ ПРОШЛА');
        }
    }

    /**
     * Покупка и создание записи о покупки
     *
     * @param TrendLogicDTO $dto
     * @param float $amount
     * @return bool
     */
    public static function buy(TrendLogicDTO $dto, float $amount): bool
    {
        $buy = BybitService::buy($dto->getPair(), $amount);
        if ($buy) {
            (new Request())
                ->setOrderId($dto->getOrderId())
                ->setType(Request::TYPE_BUY)
                ->setAmount($amount)->save();
            return true;
        }
        return false;
    }
}
