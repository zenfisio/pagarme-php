<?php

namespace PagarMe\Sdk\Customer;

trait CustomerBuilder
{
    /**
     * @param array $customerData
     * @return Customer
     */
    private function buildCustomer($customerData)
    {
        $customerData->documents = array_map(function ($document) {
            return new Document(get_object_vars($document));
        }, $customerData->documents);

        $customerData->date_created = new \DateTime(
            $customerData->date_created
        );

        return new Customer(get_object_vars($customerData));
    }

    /**
     * @param array $customerData
     * @return Customer
     */
    private function buildCustomerFromResponse($customerData)
    {
        if (is_null($customerData) || $customerData == new \stdClass()) {
            return null;
        }

        $customerData->date_created = new \DateTime(
            $customerData->date_created
        );

        return new Customer(get_object_vars($customerData));
    }
}
