<?php

namespace PagarMe\Sdk\Customer;

trait CustomerBuilder
{
    use \PagarMe\Sdk\Customer\Document\DocumentBuilder;
    /**
     * @param array $customerData
     * @return Customer
     */
    private function buildCustomer($customerData)
    {
        if (isset($customerData->documents)) {
            $customerData->documents = $this->buildDocuments(
                $customerData->documents
            );
        }

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

        if (isset($customerData->documents)) {
            $customerData->documents = $this->buildDocuments(
                $customerData->documents
            );
        }

        $customerData->date_created = new \DateTime(
            $customerData->date_created
        );

        return new Customer(get_object_vars($customerData));
    }
}
