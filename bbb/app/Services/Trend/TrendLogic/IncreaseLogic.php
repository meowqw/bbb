<?php

namespace App\Services\Trend\TrendLogic;

use App\Services\Trend\TrendLogic\DTO\TrendLogicDTO;
use Illuminate\Support\Facades\Log;

class IncreaseLogic implements TrendLogicInterface
{
    /**
     * Поведение при тренде повышение
     *
     * @param TrendLogicDTO $dto
     * @return void
     */
    public function execute(TrendLogicDTO $dto): void
    {
        Log::info('Повышение: Ордер №' . $dto->getOrderId());
        return;
    }
}
