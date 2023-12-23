<?php

namespace App\Models\PriceHistory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property float $amount
 * @property int $order_id
 */
class PriceHistory extends Model
{
    protected $table = 'price_history';

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return PriceHistory
     */
    public function setAmount(float $amount): PriceHistory
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->order_id;
    }

    /**
     * @param int $orderId
     * @return PriceHistory
     */
    public function setOrderId(int $orderId): PriceHistory
    {
        $this->order_id = $orderId;
        return $this;
    }
}
