<?php

namespace App\Services\Trend\TrendLogic;

use App\Services\Trend\TrendLogic\DTO\TrendLogicDTO;

interface TrendLogicInterface
{
    public function execute(TrendLogicDTO $dto): void;
}
