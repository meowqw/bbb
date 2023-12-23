<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $pair
 * @property string $status
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class Order extends Model
{
    const
        STATUS_OPEN = 'open',
        STATUS_CLOSE = 'close';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Order
     */
    public function setId(int $id): Order
    {
        $this->id = $id;
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
     * @return Order
     */
    public function setPair(string $pair): Order
    {
        $this->pair = $pair;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return Order
     */
    public function setStatus(string $status): Order
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreateAt(): mixed
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
