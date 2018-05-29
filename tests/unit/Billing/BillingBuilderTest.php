<?php

namespace PagarMe\SdkTests\Billing;

class BillingBuilderTest extends \PHPUnit_Framework_TestCase
{
    use \PagarMe\Sdk\Billing\BillingBuilder;

    /**
     * @test
     */
    public function mustCreateBillingCorrectly()
    {
        $payload = '{"object":"billing","name":"Trinity Moss","address":{"country":"br","state":"sp","city":"Cotia","neighborhood":"Rio Cotia","street":"Rua Matrix","street_number":"9999","zipcode":"06714360"}}';

        $billing = $this->buildBilling(json_decode($payload));

        $this->assertInstanceOf('PagarMe\Sdk\Billing\Billing', $billing);
        $this->assertInstanceOf('PagarMe\Sdk\Address\Address', $billing->getAddress());
    }
}
