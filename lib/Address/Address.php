<?php

namespace PagarMe\Sdk\Address;

class Address
{
    use \PagarMe\Sdk\Fillable;

    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $streetNumber;

    /**
     * @var string
     */
    private $zipcode;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $neighborhood;

    /**
     * @var string
     */
    private $complementary;

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
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getNeighborhood()
    {
        return $this->neighborhood;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getComplementary()
    {
        return $this->complementary;
    }
}
