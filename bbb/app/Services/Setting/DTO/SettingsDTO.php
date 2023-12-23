<?php

namespace App\Services\Setting\DTO;

class SettingsDTO
{
    private string $quotedCoin;
    private string $baseCoin;
    private string $pair;
    private float $buyPercent;
    private float $sellPercent;
    private float $bybitCommission;
    private float $orderAmount;

    /**
     * @return string
     */
    public function getQuotedCoin(): string
    {
        return $this->quotedCoin;
    }

    /**
     * @param string $quotedCoin
     * @return SettingsDTO
     */
    public function setQuotedCoin(string $quotedCoin): SettingsDTO
    {
        $this->quotedCoin = $quotedCoin;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseCoin(): string
    {
        return $this->baseCoin;
    }

    /**
     * @param string $baseCoin
     * @return SettingsDTO
     */
    public function setBaseCoin(string $baseCoin): SettingsDTO
    {
        $this->baseCoin = $baseCoin;
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
     * @return SettingsDTO
     */
    public function setPair(string $pair): SettingsDTO
    {
        $this->pair = $pair;
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
     * @return SettingsDTO
     */
    public function setBuyPercent(float $buyPercent): SettingsDTO
    {
        $this->buyPercent = $buyPercent;
        return $this;
    }

    /**
     * @return float
     */
    public function getSellPercent(): float
    {
        return $this->sellPercent;
    }

    /**
     * @param float $sellPercent
     * @return SettingsDTO
     */
    public function setSellPercent(float $sellPercent): SettingsDTO
    {
        $this->sellPercent = $sellPercent;
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
     * @return SettingsDTO
     */
    public function setBybitCommission(float $bybitCommission): SettingsDTO
    {
        $this->bybitCommission = $bybitCommission;
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
     * @return SettingsDTO
     */
    public function setOrderAmount(float $orderAmount): SettingsDTO
    {
        $this->orderAmount = $orderAmount;
        return $this;
    }
}
