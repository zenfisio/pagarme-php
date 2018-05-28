<?php

namespace PagarMe\Sdk\Customer;

use PagarMe\Sdk\AbstractHandler;
use PagarMe\Sdk\Client;
use PagarMe\Sdk\Customer\Request\CustomerCreate;
use PagarMe\Sdk\Customer\Request\CustomerGet;
use PagarMe\Sdk\Customer\Request\CustomerList;
use PagarMe\Sdk\Customer\Phone;

class CustomerHandler extends AbstractHandler
{
    use CustomerBuilder;

    /**
     * @param string $name
     * @param string $email
     * @param string $externalId
     * @param string $type
     * @param array $phoneNumbers
     * @param array $documents
     * @param Phone $phone
     */
    public function create(
        $name,
        $email,
        $externalId,
        $type,
        $country,
        $phoneNumbers,
        $documents,
        Phone $phone
    ) {
        $request = new CustomerCreate(
            $name,
            $email,
            $externalId,
            $type,
            $country,
            $phoneNumbers,
            $documents,
            $phone
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
