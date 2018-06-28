<?php

namespace PagarMe\Sdk\Shipping;

class Shipping
{
    use \PagarMe\Sdk\Fillable;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $fee;

    /**
     * @var string
     */
    private $deliveryDate;

    /**
     * @var bool
     */
    private $expedited;

    /**
     * @var \PagarMe\Sdk\Address
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
     * @return int
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    /**
     * @codeCoverageIgnore
     * @return bool
     */
    public function getExpedited()
    {
        return $this->expedited;
    }

    /**
     * @codeCoverageIgnore
     * @return \PagarMe\Sdk\Address\Address
     */
    public function getAddress()
    {
        return $this->address;
    }
}
