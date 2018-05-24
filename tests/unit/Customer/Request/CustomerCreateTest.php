<?php

namespace PagarMe\SdkTest\Customer\Request;

use PagarMe\Sdk\Customer\Request\CustomerCreate;
use PagarMe\Sdk\Customer\Customer;
use PagarMe\Sdk\RequestInterface;

class CustomerCreateTest extends \PHPUnit_Framework_TestCase
{
    const PATH            = 'customers';

    const NAME            = 'Eduardo Nascimento';
    const EMAIL           = 'eduardo@eduardo.com';
    const EXTERNAL_ID     = 'x-1234';
    const TYPE            = 'individual';
    const COUNTRY         = 'br';
    const DOCUMENT_NUMBER = '10586649727';

    /**
     * @test
     */
    public function mustPayloadBeCorrect()
    {
        $address = new \PagarMe\Sdk\Customer\Address(
            [
                'street'        => 'rua teste',
                'street_number' => 42,
                'neighborhood'  => 'centro',
                'zipcode'       => '01227200',
                'complementary' => 'Apto 42',
                'city'          => 'São Paulo',
                'state'         => 'SP',
                'country'       => 'Brasil'
            ]
        );

        $documents = [
            new \PagarMe\Sdk\Customer\Document([
                'type' => 'cpf',
                'number' => self::DOCUMENT_NUMBER
            ])
        ];

        $customerCreate = new CustomerCreate(
            self::NAME,
            self::EMAIL,
            self::EXTERNAL_ID,
            self::TYPE,
            self::COUNTRY,
            ['+5511912345678'],
            $documents,
            $address,
            new \PagarMe\Sdk\Customer\Phone(
                [
                    'ddd'    =>15,
                    'number' =>987523421
                ]
            )
        );

        $this->assertEquals(
            [
                'email'           => 'eduardo@eduardo.com',
                'external_id'     => 'x-1234',
                'type'            => 'individual',
                'country'         => 'br',
                'phone_numbers'   => ['+5511912345678'],
                'name'            => 'Eduardo Nascimento',
                'documents' => [[
                    'type' => 'cpf',
                    'number' => '10586649727'
                ]],
                'address' => [
                    'street'        => 'rua teste',
                    'street_number' => 42,
                    'neighborhood'  => 'centro',
                    'zipcode'       => '01227200',
                    'complementary' => 'Apto 42',
                    'city'          => 'São Paulo',
                    'state'         => 'SP',
                    'country'       => 'Brasil'
                ],
                'phone' => [
                    'ddd'    => 15,
                    'number' => 987523421
                ]
            ],
            $customerCreate->getPayload()
        );
    }

    /**
     * @test
     */
    public function mustPathBeCorrect()
    {
        $customerCreate = new CustomerCreate(
            self::NAME,
            self::EMAIL,
            self::EXTERNAL_ID,
            self::TYPE,
            self::COUNTRY,
            ['+5511912345678'],
            $this->getDocumentsMock(),
            $this->getAddressMock(),
            $this->getPhoneMock(),
            null,
            null
        );

        $this->assertEquals(self::PATH, $customerCreate->getPath());
    }

    /**
     * @test
     */
    public function mustMethodBeCorrect()
    {
        $customerCreate = new CustomerCreate(
            self::NAME,
            self::EMAIL,
            self::EXTERNAL_ID,
            self::TYPE,
            self::COUNTRY,
            ['+5511912345678'],
            $this->getDocumentsMock(),
            $this->getAddressMock(),
            $this->getPhoneMock(),
            null,
            null
        );

        $this->assertEquals(RequestInterface::HTTP_POST, $customerCreate->getMethod());
    }

    private function getDocumentsMock()
    {
        return [
            $this->getMockBuilder('PagarMe\Sdk\Customer\Document')
            ->disableOriginalConstructor()
            ->getMock()
        ];
    }

    private function getAddressMock()
    {
        return $this->getMockBuilder('PagarMe\Sdk\Customer\Address')
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getPhoneMock()
    {
        return $this->getMockBuilder('PagarMe\Sdk\Customer\Phone')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
