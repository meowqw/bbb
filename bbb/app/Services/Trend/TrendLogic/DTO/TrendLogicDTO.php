<?php

namespace App\Services\Trend\TrendLogic\DTO;

use App\Models\Order\Order;

class TrendLogicDTO
{
    private int $orderId;
    private float $orderAmount;
    private float $bybitCommission;
    private float $currentPairPrice;
    private float $buyPercent;
    private float $salePercent;
    private float $minOrderAmt;
    private string $pair;
    private float $quotedBalance;
    private float $baseBalance;
    private Order $order;

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * @param int $orderId
     * @return TrendLogicDTO
     */
    public function setOrderId(int $orderId): TrendLogicDTO
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @return float
     */
    public function getOrderAmount(): float
    {
        return $this->orderAmount;
    }

    /**
     * @param float $orderAmount
     * @return TrendLogicDTO
     */
    public function setOrderAmount(float $orderAmount): TrendLogicDTO
    {
        $this->orderAmount = $orderAmount;
        return $this;
    }

    /**
     * @return float
     */
    public function getBybitCommission(): float
    {
        return $this->bybitCommission;
    }

    /**
     * @param float $bybitCommission
     * @return TrendLogicDTO
     */
    public function setBybitCommission(float $bybitCommission): TrendLogicDTO
    {
        $this->bybitCommission = $bybitCommission;
        return $this;
    }

    /**
     * @return float
     */
    public function getCurrentPairPrice(): float
    {
        return $this->currentPairPrice;
    }

    /**
     * @param float $currentPairPrice
     * @return TrendLogicDTO
     */
    public function setCurrentPairPrice(float $currentPairPrice): TrendLogicDTO
    {
        $this->currentPairPrice = $currentPairPrice;
        return $this;
    }

    /**
     * @return float
     */
    public function getBuyPercent(): float
    {
        return $this->buyPercent;
    }

    /**
     * @param float $buyPercent
     * @return TrendLogicDTO
     */
    public function setBuyPercent(float $buyPercent): TrendLogicDTO
    {
        $this->buyPercent = $buyPercent;
        return $this;
    }

    /**
     * @return float
     */
    public function getMinOrderAmt(): float
    {
        return $this->minOrderAmt;
    }

    /**
     * @param float $minOrderAmt
     * @return TrendLogicDTO
     */
    public function setMinOrderAmt(float $minOrderAmt): TrendLogicDTO
    {
        $this->minOrderAmt = $minOrderAmt;
        return $this;
    }

    /**
     * @return string
     */
    public function getPair(): string
    {
        return $this->pair;
    }

    /**
     * @param string $pair
     * @return TrendLogicDTO
     */
    public function setPair(string $pair): TrendLogicDTO
    {
        $this->pair = $pair;
        return $this;
    }

    /**
     * @return float
     */
    public function getSalePercent(): float
    {
        return $this->salePercent;
    }

    /**
     * @param float $salePercent
     * @return TrendLogicDTO
     */
    public function setSalePercent(float $salePercent): TrendLogicDTO
    {
        $this->salePercent = $salePercent;
        return $this;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @param Order $order
     * @return TrendLogicDTO
     */
    public function setOrder(Order $order): TrendLogicDTO
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @return float
     */
    public function getQuotedBalance(): float
    {
        return $this->quotedBalance;
    }

    /**
     * @param float $quotedBalance
     * @return TrendLogicDTO
     */
    public function setQuotedBalance(float $quotedBalance): TrendLogicDTO
    {
        $this->quotedBalance = $quotedBalance;
        return $this;
    }

    /**
     * @return float
     */
    public function getBaseBalance(): float
    {
        return $this->baseBalance;
    }

    /**
     * @param float $baseBalance
     * @return TrendLogicDTO
     */
    public function setBaseBalance(float $baseBalance): TrendLogicDTO
    {
        $this->baseBalance = $baseBalance;
        return $this;
    }
}
