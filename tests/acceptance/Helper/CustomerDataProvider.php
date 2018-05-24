<?php

namespace PagarMe\Acceptance\Helper;

use PagarMe\Sdk\Customer\Address;
use PagarMe\Sdk\Customer\Phone;

trait CustomerDataProvider
{

    protected function getCustomerName()
    {
        return 'Joao Silva';
    }

    protected function getCustomerEmail()
    {
        return uniqid('user') . '@email.com';
    }

    protected function getCustomerExternalId()
    {
        return 'x-1234';
    }

    protected function getCustomerType()
    {
        return 'individual';
    }

    protected function getCustomerCountry()
    {
        return 'br';
    }

    protected function getCustomerPhoneNumbers()
    {
        return ['+5511912345678'];
    }

    protected function getCustomerDocumentNumber()
    {
        return '78863832064';
    }

    protected function getValidCustomerData()
    {
        return [
            'document_number' => $this->getCustomerDocumentNumber(),
            'email'           => $this->getCustomerEmail(),
            'name'            => $this->getCustomerName(),
            'address' => [
                'city'          => 'sao paulo,',
                'complementary' => 'apto,',
                'country'       => 'Brasil,',
                'neighborhood'  => 'pinheiros,',
                'state'         => 'SP,',
                'street'        => 'rua qualquer,',
                'street_number' => '13,',
                'zipcode'       => '05444040,',
            ],
            'phone' => [
                'ddd'    => 11,
                'ddi'    => 55,
                'number' => 999887766,
            ]
        ];
    }
}
