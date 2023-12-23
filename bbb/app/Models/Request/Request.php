<?php

namespace App\Models\Request;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $type
 * @property float $amount
 * @property int $order_id
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class Request extends Model
{
    const
        TYPE_BUY = 'buy',
        TYPE_SALE = 'sale';

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Request
     */
    public function setType(string $type): Request
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return Request
     */
    public function setAmount(float $amount): Request
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
     * @param int $order_id
     * @return Request
     */
    public function setOrderId(int $order_id): Request
    {
        $this->order_id = $order_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt(): mixed
    {
        return $this->created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt(): mixed
    {
        return $this->updated_at;
    }
}
