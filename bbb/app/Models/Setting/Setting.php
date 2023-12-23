<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $type
 * @property string $name
 * @property string $value
 * @property string $code
 */
class Setting extends Model
{
    const
        SERVICE_STATUS_CODE = 'service_status',
        ORDER_AMOUNT_CODE = 'order_amount',
        BUY_PERCENT_DIFFERENCE_CODE = 'buy_percent_difference',
        SELL_PERCENT_DIFFERENCE_CODE = 'sell_percent_difference',
        PRICE_TREND_LIMIT_CODE = 'price_trend_limit',
        BYBIT_COMMISSION_CODE = 'bybit_commission',
        BASE_COIN = 'base_coin',
        QUOTED_COIN = 'quoted_coin';

    const
        STATUS_SERVICE_ON = 'on',
        STATUS_SERVICE_OFF = 'off';

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Setting
     */
    public function setCode(string $code): Setting
    {
        $this->code = $code;
        return $this;
    }
}
