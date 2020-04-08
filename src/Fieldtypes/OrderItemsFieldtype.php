<?php

namespace DoubleThreeDigital\SimpleCommerce\Fieldtypes;

use DoubleThreeDigital\SimpleCommerce\Helpers\Currency;
use Statamic\Fields\Fieldtype;

class OrderItemsFieldtype extends Fieldtype
{
    protected $icon = 'generic';

    public function preload()
    {
        return [
            'currency' => (new Currency())->primary(),
        ];
    }

    public function preProcess($data)
    {
        return $data;
    }

    public function process($data)
    {
        return $data;
    }

    public static function title()
    {
        return 'Order Items';
    }

    public function component(): string
    {
        return 'order-items';
    }
}
