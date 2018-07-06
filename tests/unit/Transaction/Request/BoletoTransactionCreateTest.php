<?php

namespace PagarMe\SdkTest\Transaction\Request;

use PagarMe\Sdk\Transaction\Request\BoletoTransactionCreate;
use PagarMe\Sdk\Transaction\BoletoTransaction;
use PagarMe\Sdk\Item\ItemCollection;
use PagarMe\Sdk\Item\Item;
use PagarMe\Sdk\Customer\Document\DocumentCollection;
use PagarMe\Sdk\Customer\Document\Document;
use PagarMe\Sdk\SplitRule\SplitRuleCollection;
use PagarMe\Sdk\SplitRule\SplitRule;
use PagarMe\Sdk\Recipient\Recipient;
use PagarMe\Sdk\RequestInterface;

class BoletoTransactionCreateTest extends \PHPUnit_Framework_TestCase
{
    const PATH   = 'transactions';

    const CARD_ID = 1;

    public function boletoOptions()
    {
        $customer = $this->getCustomerMock();

        return [
            [null],
            [date('Y-m-d', strtotime("tomorrow"))],
            [date('Y-m-d', strtotime("+15 days"))]
        ];
    }

    /**
     * @dataProvider boletoOptions
     * @test
     */
    public function mustPayloadBeCorrect($expirationDate)
    {
        $transaction =  $this->createTransaction($expirationDate);
        $transactionCreate = new BoletoTransactionCreate($transaction);

        $this->assertEquals(
            [
                'amount'                 => 1337,
                'payment_method'         => 'boleto',
                'postback_url'           => 'example.com/postback',
                'boleto_expiration_date' => $expirationDate,
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
                'metadata' => null,
                'async' => null,
                'boleto_instructions' => null,
                'soft_descriptor' => null
            ],
            $transactionCreate->getPayload()
        );
    }

    /**
     * @test
     */
    public function mustPayloadContainSplitRule()
    {
        $customerMock = $this->getCustomerMock();

        $rules = new SplitRuleCollection();
        $rules[]= new SplitRule([
            'amount'                => 100,
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

        $expirationDate = strtotime("tomorrow");

        $transaction =  new BoletoTransaction(
            [
                'amount'                 => 1337,
                'postback_url'           => 'example.com/postback',
                'customer'               => $customerMock,
                'boleto_expiration_date' => $expirationDate,
                'split_rules'            => $rules
            ]
        );

        $transactionCreate = new BoletoTransactionCreate(
            $transaction,
            null,
            null,
            ['splitRules' => $rules]
        );

        $this->assertEquals(
            [
                'amount'                 => 1337,
                'payment_method'         => 'boleto',
                'postback_url'           => 'example.com/postback',
                'boleto_expiration_date' => $expirationDate,
                'customer' => [
                    'name'            => 'Eduardo Nascimento',
                    'external_id'     => 'x-1234',
                    'type'            => 'individual',
                    'country'         => 'br',
                    'phone_numbers'   => ['5511912345678'],
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
                        'percentage'            => 10,
                        'recipient_id'          => 3,
                        'liable'                => false,
                        'charge_processing_fee' => false
                    ]
                ],
                'metadata' => null,
                'async' => null,
                'boleto_instructions' => null,
                'soft_descriptor' => null
            ],
            $transactionCreate->getPayload()
        );
    }

    /**
     * @test
     */
    public function mustPathBeCorrect()
    {
        $transaction =  $this->getMockBuilder('PagarMe\Sdk\Transaction\BoletoTransaction')
            ->disableOriginalConstructor()
            ->getMock();

        $transactionCreate = new BoletoTransactionCreate($transaction);

        $this->assertEquals(self::PATH, $transactionCreate->getPath());
    }

    /**
     * @test
     */
    public function mustMethodBeCorrect()
    {
        $transaction =  $this->getMockBuilder('PagarMe\Sdk\Transaction\BoletoTransaction')
            ->disableOriginalConstructor()
            ->getMock();

        $transactionCreate = new BoletoTransactionCreate($transaction);

        $this->assertEquals(RequestInterface::HTTP_POST, $transactionCreate->getMethod());
    }

    private function createTransaction($expirationDate)
    {
        $customerMock = $this->getCustomerMock();

        $transaction =  new BoletoTransaction(
            [
                'amount'                 => 1337,
                'postback_url'          => 'example.com/postback',
                'customer'               => $customerMock,
                'boleto_expiration_date' => $expirationDate
            ]
        );

        return $transaction;
    }

    public function getCustomerMock()
    {
        $customerMock = $this->getMockBuilder('PagarMe\Sdk\Customer\Customer')
            ->disableOriginalConstructor()
            ->getMock();

        $documents = new DocumentCollection();
        $document = new Document([                    'type' => 'cpf',
            'number' => '10586649727'
        ]);
        $documents[] = $document;

        $customerMock->method('getExternalId')->willReturn('x-1234');
        $customerMock->method('getType')->willReturn('individual');
        $customerMock->method('getCountry')->willReturn('br');
        $customerMock->method('getPhoneNumbers')->willReturn(['+5511912345678']);
        $customerMock->method('getDocumentNumber')->willReturn('10586649727');
        $customerMock->method('getEmail')->willReturn('eduardo@eduardo.com');
        $customerMock->method('getName')->willReturn('Eduardo Nascimento');
        $customerMock->method('getDocuments')->willReturn($documents);

        return $customerMock;
    }

    /**
     * @test
     */
    public function mustPayloadContainSoftDescriptor()
    {

        $customerMock = $this->getCustomerMock();

        $transaction =  new BoletoTransaction(
            [
                'amount'                 => 1338,
                'postback_url'           => 'example.com/postback',
                'customer'               => $customerMock,
                'soft_descriptor'        => "Minha loja"
            ]
        );

        $transactionCreate = new boletoTransactionCreate(
            $transaction
        );

        $this->assertEquals(
            [
                'amount'                 => 1338,
                'payment_method'         => 'boleto',
                'postback_url'           => 'example.com/postback',
                'boleto_expiration_date' => null,
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
                'metadata' => null,
                'async' => null,
                'boleto_instructions' => null,
                'soft_descriptor' => 'Minha loja'
            ],
            $transactionCreate->getPayload()
        );
    }
}
