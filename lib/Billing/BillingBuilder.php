<?php

namespace PagarMe\Sdk\Billing;

use PagarMe\Sdk\Address\Address;

trait BillingBuilder
{
    /**
     * @param array $billingData
     * @return Billing
     */
    private function buildBilling($billingData)
    {
        $billing = get_object_vars($billingData);

        $billingData->address = new Address($billing['address']);

        return new Billing(get_object_vars($billingData));
    }
}
