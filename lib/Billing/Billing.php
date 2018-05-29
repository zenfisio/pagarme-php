<?php

namespace PagarMe\Sdk\Billing;

class Billing
{
    use \PagarMe\Sdk\Fillable;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \PagarMe\Sdk\Address\Address
     */
    private $address;

    /**
     * @param array $arrayData
     */
    public function __construct($arrayData)
    {
        $this->fill($arrayData);
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @codeCoverageIgnore
     * @return \PagarMe\Sdk\Address
     */
    public function getAddress()
    {
        return $this->address;
    }
}
