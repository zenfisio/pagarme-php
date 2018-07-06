<?php

namespace PagarMe\Acceptance\Helper;

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

    protected function getCustomerPhoneDdd()
    {
        return '11';
    }

    protected function getCustomerPhoneNumber()
    {
        return '912345678';
    }

    protected function getCustomerPhoneNumbers()
    {
        return ['+55' . $this->getCustomerPhoneDdd() . $this->getCustomerPhoneNumber()];
    }

    protected function getCustomerDocumentNumber()
    {
        return '78863832064';
    }

    protected function getValidCustomerData()
    {
        return [
            'email'   => $this->getCustomerEmail(),
            'name'    => $this->getCustomerName(),
            'country' => $this->getCustomerCountry()
        ];
    }
}
