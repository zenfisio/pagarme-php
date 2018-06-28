<?php

namespace PagarMe\Sdk\Shipping;

use PagarMe\Sdk\Address\Address;

trait ShippingBuilder
{
    /**
     * @param array $shippingData
     * @return Shipping
     */
    private function buildShipping($shippingData)
    {
        $shipping = get_object_vars($shippingData);

        $shippingData->address = new Address($shipping['address']);

        return new Shipping(get_object_vars($shippingData));
    }
}
