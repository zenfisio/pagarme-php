<?php

namespace PagarMe\SdkTests\Shipping;

class ShippingBuilderTest extends \PHPUnit_Framework_TestCase
{
    use \PagarMe\Sdk\Shipping\ShippingBuilder;

    /**
     * @test
     */
    public function mustCreateShippingCorrectly()
    {
        $payload = '{"object":"shipping","name":"Trinity Moss","fee":"500","delivery_date":"2018-07-03","expedited":"true","address":{"country":"br","state":"sp","city":"Cotia","neighborhood":"Rio Cotia","street":"Rua Matrix","street_number":"9999","zipcode":"06714360"}}';

        $shipping = $this->buildShipping(json_decode($payload));

        $this->assertInstanceOf('PagarMe\Sdk\Shipping\Shipping', $shipping);
        $this->assertInstanceOf('PagarMe\Sdk\Address\Address', $shipping->getAddress());
    }
}
