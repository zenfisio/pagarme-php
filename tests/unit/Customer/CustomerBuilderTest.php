<?php

namespace PagarMe\SdkTests\Customer;

class CustomerBuilderTest extends \PHPUnit_Framework_TestCase
{
    use \PagarMe\Sdk\Customer\CustomerBuilder;

    /**
     * @test
     */
    public function mustCreateCustomerCorrectly()
    {
        $payload = '{"object":"customer","external_id":"x-1234","type":"individual","country":"br","documents":[{"object":"document","type":"cpf","number":"25123317171"}],"name":"John Doe","email":"john@test.com","date_created":"2016-12-28T19:38:28.618Z","id":122444,"phones":[{"object":"phone","ddi":"55","ddd":"11","number":"44445555","id":65844}]}';

        $customer = $this->buildCustomer(json_decode($payload));

        $this->assertInstanceOf('PagarMe\Sdk\Customer\Customer', $customer);
        $this->assertInstanceOf('\DateTime', $customer->getDateCreated());
    }

    /**
     * @test
     */
    public function mustCreateCustomerCorrectlyFromResponse()
    {

        $response = '{"phone":{"object":"phone","ddi":"55","ddd":"11","number":"987654321","id":121118},"customer":{"object":"customer","id":76758,"external_id":"x-1234","type":"individual","country":"br","name":"AardvarkdaSilva","email":"aardvark.silva@gmail.com","phones":null,"birthday":null,"date_created":"2016-06-29T16:18:23.544Z","documents":[{"object":"document","type":"cpf","number":"25123317171"}]}}';

        $data = json_decode($response);

        $customer = $this->buildCustomerFromResponse($data->customer, $data->phone);

        $this->assertInstanceOf('PagarMe\Sdk\Customer\Customer', $customer);
        $this->assertInstanceOf('\DateTime', $customer->getDateCreated());
    }

    /**
     * @test
     */
    public function mustNotCreateCustomerFromResponse()
    {
        $response = '{"phone":{"object":"phone","ddi":"55","ddd":"11","number":"987654321","id":121118},"customer":null}';

        $data = json_decode($response);

        $customer = $this->buildCustomerFromResponse($data->customer, $data->phone);

        $this->assertNull($customer);
    }
}
