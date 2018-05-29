<?php

namespace PagarMe\SdkTest\Transaction\Request;

use PagarMe\Sdk\Transaction\Request\CreditCardTransactionCreate;
use PagarMe\Sdk\Transaction\CreditCardTransaction;
use PagarMe\Sdk\SplitRule\SplitRuleCollection;
use PagarMe\Sdk\SplitRule\SplitRule;
use PagarMe\Sdk\Recipient\Recipient;
use PagarMe\Sdk\RequestInterface;

class CreditCardTransactionCreateTest extends \PHPUnit_Framework_TestCase
{
    const PATH   = 'transactions';

    const CARD_ID   = 1;
    const CARD_HASH = 'FC1mH7XLFU5fjPAzDsP0ogeAQh3qXRpHzkIrgDz64lITBUGwio67zm';

    public function installmentsProvider()
    {
        return [
            [1,true,null, null],
            [3,true, 'example.com', 'Sua Loja', true],
            [12,false, 'example.com', null, false],
            [rand(1, 12), false, null, 'Outra Loja']
        ];
    }

    /**
     * @dataProvider installmentsProvider
     * @test
     */
    public function mustPayloadBeCorrect(
        $installments,
        $capture,
        $postbackUrl,
        $softDescriptor,
        $async = null
    ) {
        $transaction =  $this->getTransaction(
            $installments,
            $capture,
            $postbackUrl,
            $softDescriptor,
            $async
        );

        $transactionCreate = new CreditCardTransactionCreate($transaction);

        $this->assertEquals(
            [
                'amount'         => 1337,
                'card_id'        => self::CARD_ID,
                'installments'   => $installments,
                'payment_method' => 'credit_card',
                'capture'        => $capture,
                'postback_url'   => $postbackUrl,
                'customer' => [
                    'external_id'     => 'x-1234',
                    'type'            => 'individual',
                    'country'         => 'br',
                    'phone_numbers'   => ['+5511912345678'],
                    'name'            => 'Eduardo Nascimento',
                    'email'           => 'eduardo@eduardo.com',
                    'documents'       => [[
                        'type' => 'cpf',
                        'number' => '10586649727'
                    ]]
                ],
                'billing' => [
                    'name' => 'Trinity Moss',
                    'address' => [
                        "country" => "br",
                        "state" => "sp",
                        "city" => "Cotia",
                        "neighborhood" => "Rio Cotia",
                        "street" => "Rua Matrix",
                        "street_number" => "9999",
                        "zipcode" => "06714360"
                    ]
                ],
                'metadata'        => null,
                'soft_descriptor' => $softDescriptor,
                'async'           => $async
            ],
            $transactionCreate->getPayload()
        );
    }

    /**
     * @dataProvider installmentsProvider
     * @test
     */
    public function mustNotContainDocumentsDataOnPayload(
        $installments,
        $capture,
        $postbackUrl
    ) {
        $customerMock = $this->getBlankCustomerMock();
        $cardMock     = $this->getCardMock();

        $transaction =  new CreditCardTransaction(
            [
                'amount'       => 1337,
                'card'         => $cardMock,
                'customer'     => $customerMock,
                'installments' => $installments,
                'capture'      => $capture,
                'postbackUrl'  => $postbackUrl
            ]
        );

        $transactionCreate = new CreditCardTransactionCreate($transaction);

        $this->assertEquals(
            [
                'amount'         => 1337,
                'card_id'        => self::CARD_ID,
                'installments'   => $installments,
                'payment_method' => 'credit_card',
                'capture'        => $capture,
                'postback_url'   => $postbackUrl,
                'customer' => [
                    'name'            => null,
                    'external_id'     => null,
                    'type'            => null,
                    'country'         => null,
                    'phone_numbers'   => null,
                    'documents'       => null,
                    'email'           => null,
                ],
                'metadata'        => null,
                'soft_descriptor' => null,
                'async'           => null
            ],
            $transactionCreate->getPayload()
        );
    }

    /**
     * @test
     */
    public function mustPayloadContainCustomerId()
    {
        $cardMock   = $this->getCardMock();

        $customer = $this->getBlankCustomerMock();
        $customer->method('getId')->willReturn(12345);

        $transaction =  new CreditCardTransaction(
            [
                'amount'       => 1337,
                'card'         => $cardMock,
                'customer'     => $customer,
                'installments' => 1,
                'capture'      => false,
                'postback_url' => null
            ]
        );

        $transactionCreate = new CreditCardTransactionCreate($transaction);

        $this->assertEquals(
            [
                'amount'         => 1337,
                'card_id'        => self::CARD_ID,
                'installments'   => 1,
                'payment_method' => 'credit_card',
                'capture'        => false,
                'postback_url'   => null,
                'customer' => [
                    'id'              => 12345,
                    'name'            => null,
                    'external_id'     => null,
                    'type'            => null,
                    'country'         => null,
                    'phone_numbers'   => null,
                    'documents'       => null,
                    'email'           => null
                ],
                'metadata'        => null,
                'soft_descriptor' => null,
                'async'           => null

            ],
            $transactionCreate->getPayload()
        );
    }

    /**
     * @test
     */
    public function mustPayloadContainMonetarySplitRules()
    {
        $customerMock = $this->getFullCustomerMock();
        $cardMock     = $this->getCardMock();

        $rules = new SplitRuleCollection();
        $rules[]= new SplitRule([
            'amount'                => 100,
            'recipient'             => new Recipient(['id' => 1]),
            'liable'                => true,
            'charge_processing_fee' => true
        ]);
        $rules[]= new SplitRule([
            'amount'                => 1237,
            'recipient'             => new Recipient(['id' => 3]),
            'liable'                => false,
            'charge_processing_fee' => false
        ]);

        $transaction =  new CreditCardTransaction(
            [
                'amount'       => 1337,
                'card'         => $cardMock,
                'customer'     => $customerMock,
                'installments' => 1,
                'capture'      => false,
                'postback_url' => null,
                'split_rules'  => $rules
            ]
        );

        $transactionCreate = new CreditCardTransactionCreate($transaction);

        $this->assertEquals(
            [
                'amount'         => 1337,
                'card_id'        => self::CARD_ID,
                'installments'   => 1,
                'payment_method' => 'credit_card',
                'capture'        => false,
                'postback_url'   => null,
                'customer' => [
                    'name'            => 'Eduardo Nascimento',
                    'external_id'     => 'x-1234',
                    'type'            => 'individual',
                    'country'         => 'br',
                    'phone_numbers'   => ['+5511912345678'],
                    'email'           => 'eduardo@eduardo.com',
                    'documents'       => [[
                        'type' => 'cpf',
                        'number' => '10586649727'
                    ]]
                ],
                'split_rules' => [
                    0 => [
                        'amount'                => 100,
                        'recipient_id'          => 1,
                        'liable'                => true,
                        'charge_processing_fee' => true
                    ],
                    1 => [
                        'amount'                => 1237,
                        'recipient_id'          => 3,
                        'liable'                => false,
                        'charge_processing_fee' => false
                    ]
                ],
                'metadata'        => null,
                'soft_descriptor' => null,
                'async'           => null
            ],
            $transactionCreate->getPayload()
        );
    }

    public function mustPayloadContainBilling()
    {
        $customerMock = $this->getFullCustomerMock();
        $cardMock     = $this->getCardMock();
        $billing      = $this->getBillingMock();

        $transaction =  new CreditCardTransaction([
            'amount'       => 1337,
            'card'         => $cardMock,
            'customer'     => $customerMock,
            'billing'      => $billing,
            'installments' => 1,
            'capture'      => false,
            'postback_url' => null,
        ]);

        $transactionCreate = new CreditCardTransactionCreate($transaction);

        $this->assertEquals(
            [
                'amount'         => 1337,
                'card_id'        => self::CARD_ID,
                'installments'   => $installments,
                'payment_method' => 'credit_card',
                'capture'        => $capture,
                'postback_url'   => $postbackUrl,
                'customer' => [
                    'external_id'     => 'x-1234',
                    'type'            => 'individual',
                    'country'         => 'br',
                    'phone_numbers'   => ['+5511912345678'],
                    'name'            => 'Eduardo Nascimento',
                    'email'           => 'eduardo@eduardo.com',
                    'documents'       => [[
                        'type' => 'cpf',
                        'number' => '10586649727'
                    ]]
                ],
                'billing' => [
                    'name' => 'Trinity Moss',
                    'address' => [
                        "country" => "br",
                        "state" => "sp",
                        "city" => "Cotia",
                        "neighborhood" => "Rio Cotia",
                        "street" => "Rua Matrix",
                        "street_number" => "9999",
                        "zipcode" => "06714360"
                    ]
                ],
                'metadata'        => null,
                'soft_descriptor' => null,
                'async'           => null
            ],
            $transaction->getPayload()
        );
    }

    /**
     * @test
     */
    public function mustPayloadContainPercentageSplitRules()
    {
        $customerMock = $this->getFullCustomerMock();
        $cardMock     = $this->getCardMock();

        $rules = new SplitRuleCollection();
        $rules[]= new SplitRule([
            'percentage'            => 90,
            'recipient'             => new Recipient(['id' => 1]),
            'liable'                => true,
            'charge_processing_fee' => true
        ]);
        $rules[]= new SplitRule([
            'percentage'            => 10,
            'recipient'             => new Recipient(['id' => 3]),
            'liable'                => false,
            'charge_processing_fee' => false
        ]);

        $transaction =  new CreditCardTransaction(
            [
                'amount'       => 1337,
                'card'         => $cardMock,
                'customer'     => $customerMock,
                'installments' => 1,
                'capture'      => false,
                'postback_url' => null,
                'split_rules'  => $rules
            ]
        );

        $transactionCreate = new CreditCardTransactionCreate($transaction);

        $this->assertEquals(
            [
                'amount'         => 1337,
                'card_id'        => self::CARD_ID,
                'installments'   => 1,
                'payment_method' => 'credit_card',
                'capture'        => false,
                'postback_url'   => null,
                'customer' => [
                    'name'            => 'Eduardo Nascimento',
                    'external_id'     => 'x-1234',
                    'type'            => 'individual',
                    'country'         => 'br',
                    'phone_numbers'   => ['+5511912345678'],
                    'email'           => 'eduardo@eduardo.com',
                    'documents'       => [[
                        'type' => 'cpf',
                        'number' => '10586649727'
                    ]]
                ],
                'split_rules' => [
                    0 => [
                        'percentage'            => 90,
                        'recipient_id'          => 1,
                        'liable'                => true,
                        'charge_processing_fee' => true
                    ],
                    1 => [
                        'percentage'            => 10,
                        'recipient_id'          => 3,
                        'liable'                => false,
                        'charge_processing_fee' => false
                    ]
                ],
                'metadata'        => null,
                'soft_descriptor' => null,
                'async'           => null
            ],
            $transactionCreate->getPayload()
        );
    }

    /**
     * @test
     */
    public function mustPayloadContainCardCvv()
    {
        $customerMock = $this->getFullCustomerMock();
        $cardMock     = $this->getCardMock();

        $transaction =  new CreditCardTransaction(
            [
                'amount'       => 1337,
                'card'         => $cardMock,
                'customer'     => $customerMock,
                'installments' => 1,
                'capture'      => false,
                'postback_url' => null,
                'card_cvv'     => 123
            ]
        );

        $transactionCreate = new CreditCardTransactionCreate($transaction);

        $payload = $transactionCreate->getPayload();

        $this->assertArrayHasKey('card_cvv', $payload);
        $this->assertEquals(123, $payload['card_cvv']);
    }

    /**
     * @dataProvider installmentsProvider
     * @test
     */
    public function mustPayloadContainCardHash($installments, $capture, $postbackUrl)
    {
        $customerMock = $this->getFullCustomerMock();
        $cardMock = $this->getMockBuilder('PagarMe\Sdk\Card\Card')
            ->disableOriginalConstructor()
            ->getMock();

        $cardMock->method('getHash')
            ->willReturn(self::CARD_HASH);

        $transaction =  new CreditCardTransaction(
            [
                'amount'       => 1337,
                'card'         => $cardMock,
                'customer'     => $customerMock,
                'installments' => $installments,
                'capture'      => $capture,
                'postbackUrl'  => $postbackUrl
            ]
        );

        $transactionCreate = new CreditCardTransactionCreate($transaction);

        $this->assertEquals(
            [
                'amount'         => 1337,
                'card_hash'      => self::CARD_HASH,
                'installments'   => $installments,
                'payment_method' => 'credit_card',
                'capture'        => $capture,
                'postback_url'   => $postbackUrl,
                'customer' => [
                    'name'            => 'Eduardo Nascimento',
                    'external_id'     => 'x-1234',
                    'type'            => 'individual',
                    'country'         => 'br',
                    'phone_numbers'   => ['+5511912345678'],
                    'email'           => 'eduardo@eduardo.com',
                    'documents'       => [[
                        'type' => 'cpf',
                        'number' => '10586649727'
                    ]]
                ],
                'metadata'        => null,
                'soft_descriptor' => null,
                'async'           => null
            ],
            $transactionCreate->getPayload()
        );
    }

    /**
     * @test
     */
    public function mustPathBeCorrect()
    {
        $transaction =  $this->getTransaction(
            rand(1, 12),
            false,
            null,
            null,
            null
        );

        $transactionCreate = new CreditCardTransactionCreate($transaction);

        $this->assertEquals(self::PATH, $transactionCreate->getPath());
    }

    /**
     * @test
     */
    public function mustMethodBeCorrect()
    {
        $transaction =  $this->getTransaction(
            rand(1, 12),
            false,
            null,
            null,
            null
        );

        $transactionCreate = new CreditCardTransactionCreate($transaction);

        $this->assertEquals(RequestInterface::HTTP_POST, $transactionCreate->getMethod());
    }

    private function getTransaction(
        $installments,
        $capture,
        $postbackUrl,
        $softDescriptor,
        $async
    ) {
        $customerMock = $this->getFullCustomerMock();
        $cardMock     = $this->getCardMock();
        $billingMock  = $this->getBillingMock();

        $transaction =  new CreditCardTransaction(
            [
                'amount'         => 1337,
                'card'           => $cardMock,
                'customer'       => $customerMock,
                'billing'        => $billingMock,
                'installments'   => $installments,
                'capture'        => $capture,
                'postbackUrl'    => $postbackUrl,
                'softDescriptor' => $softDescriptor,
                'async'          => $async
            ]
        );

        return $transaction;
    }

    public function getCardMock()
    {
        $cardMock = $this->getMockBuilder('PagarMe\Sdk\Card\Card')
            ->disableOriginalConstructor()
            ->getMock();

        $cardMock->method('getId')
            ->willReturn(self::CARD_ID);

        return $cardMock;
    }

    public function getAddressMock()
    {
        $addressMock = $this->getMockBuilder('PagarMe\Sdk\Address\Address')
            ->disableOriginalConstructor()
            ->getMock();

        $addressMock->method('getCountry')->willReturn('br');
        $addressMock->method('getState')->willReturn('sp');
        $addressMock->method('getCity')->willReturn('Cotia');
        $addressMock->method('getNeighborhood')->willReturn('Rio Cotia');
        $addressMock->method('getStreet')->willReturn('Rua Matrix');
        $addressMock->method('getStreetNumber')->willReturn('9999');
        $addressMock->method('getZipcode')->willReturn('06714360');

        return $addressMock;
    }

    public function getBillingMock()
    {
        $billingMock = $this->getMockBuilder('PagarMe\Sdk\Billing\Billing')
            ->disableOriginalConstructor()
            ->getMock();

        $billingMock->method('getName')->willReturn('Trinity Moss');
        $billingMock->method('getAddress')->willReturn($this->getAddressMock());

        return $billingMock;
    }


    public function getFullCustomerMock()
    {
        $customerMock = $this->getMockBuilder('PagarMe\Sdk\Customer\Customer')
            ->disableOriginalConstructor()
            ->getMock();

        $customerMock->method('getExternalId')->willReturn('x-1234');
        $customerMock->method('getType')->willReturn('individual');
        $customerMock->method('getCountry')->willReturn('br');
        $customerMock->method('getPhoneNumbers')->willReturn(['+5511912345678']);
        $customerMock->method('getDocumentNumber')->willReturn('10586649727');
        $customerMock->method('getEmail')->willReturn('eduardo@eduardo.com');
        $customerMock->method('getName')->willReturn('Eduardo Nascimento');
        $customerMock->method('getDocuments')->willReturn([[
            'type' => 'cpf',
            'number' => '10586649727'
        ]]);

        return $customerMock;
    }

    public function getBlankCustomerMock()
    {
        $customerMock = $this->getMockBuilder('PagarMe\Sdk\Customer\Customer')
            ->disableOriginalConstructor()
            ->getMock();

        $customerMock->method('getExternalId')->willReturn(null);
        $customerMock->method('getType')->willReturn(null);
        $customerMock->method('getCountry')->willReturn(null);
        $customerMock->method('getDocumentNumber')->willReturn(null);
        $customerMock->method('getEmail')->willReturn(null);
        $customerMock->method('getName')->willReturn(null);

        return $customerMock;
    }
}
