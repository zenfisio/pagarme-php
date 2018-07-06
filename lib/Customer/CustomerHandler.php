<?php

namespace PagarMe\Sdk\Customer;

use PagarMe\Sdk\AbstractHandler;
use PagarMe\Sdk\Client;
use PagarMe\Sdk\Customer\Document\DocumentCollection;
use PagarMe\Sdk\Customer\Request\CustomerCreate;
use PagarMe\Sdk\Customer\Request\CustomerGet;
use PagarMe\Sdk\Customer\Request\CustomerList;

class CustomerHandler extends AbstractHandler
{
    use CustomerBuilder;

    /**
     * @param string $name
     * @param string $email
     * @param string $externalId
     * @param string $type
     * @param array $phoneNumbers
     * @param DocumentCollection $documents
     */
    public function create(
        $name,
        $email,
        $externalId,
        $type,
        $country,
        $phoneNumbers,
        DocumentCollection $documents
    ) {
        $request = new CustomerCreate(
            $name,
            $email,
            $externalId,
            $type,
            $country,
            $phoneNumbers,
            $documents
        );

        $response = $this->client->send($request);

        return $this->buildCustomer($response);
    }

    /**
     * @param int $customerId
     */
    public function get($customerId)
    {
        $request = new CustomerGet($customerId);
        $response = $this->client->send($request);

        return $this->buildCustomer($response);
    }

    /**
     * @param int $page
     * @param int $count
     */
    public function getList($page = null, $count = null)
    {
        $request = new CustomerList($page, $count);
        $response = $this->client->send($request);

        $customers = [];
        foreach ($response as $customerResponse) {
            $customers[] = $this->buildCustomer($customerResponse);
        }

        return $customers;
    }
}
