<?php

namespace PagarMe\SdkTest\Subscription\Request;

use PagarMe\Sdk\Subscription\Request\BoletoSubscriptionCreate;
use PagarMe\Sdk\SplitRule\SplitRuleCollection;
use PagarMe\Sdk\Recipient\Recipient;
use PagarMe\Sdk\RequestInterface;

class BoletoSubscriptionCreateTest extends \PHPUnit_Framework_TestCase
{
    const PATH   = 'subscriptions';

    const PLAN_ID             = 123;
    const PLAN_PAYMENT_METHOD = 'boleto';

    const POSTBACK_URL   = 'http://myhost.com/postback';

    const CUSTOMER_NAME           = 'John Doe';
    const CUSTOMER_EMAIL          = 'john@test.com';
    const CUSTOMER_EXTERNAL_ID    = 'x-1234';
    const CUSTOMER_TYPE           = 'individual';
    const CUSTOMER_COUNTRY        = 'br';
    const CUSTOMER_DOCUMENTNUMBER = '576981209';
    const CUSTOMER_DOCUMENTTYPE = 'cpf';

    const PHONE_DDD    = '11';
    const PHONE_NUMBER = '44445555';

    const ADDRESS_STREET       = 'Rua teste';
    const ADDRESS_STREETNUMBER = '123';
    const ADDRESS_NEIGHBORHOOD = 'Centro';
    const ADDRESS_ZIPCODE      = '01034020';

    const SPLIT_RULE_RECIPIENT_ID_1 = 're_cj2wd5ul500d4946do7qtjrvk';
    const SPLIT_RULE_RECIPIENT_ID_2 = 're_cj2wd5u2600fecw6eytgcbkd0';
    const SPLIT_RULE_VALUE = 50;

    private function getConfiguredCustomerGenericMockForPayloadTest()
    {
        $customerMock = $this->getMockBuilder('PagarMe\Sdk\Customer\Customer')
            ->disableOriginalConstructor()
            ->getMock();

        $customerMock->method('getName')
            ->willReturn(self::CUSTOMER_NAME);
        $customerMock->method('getType')
            ->willReturn(self::CUSTOMER_TYPE);
        $customerMock->method('getExternalId')
            ->willReturn(self::CUSTOMER_EXTERNAL_ID);
        $customerMock->method('getCountry')
            ->willReturn(self::CUSTOMER_COUNTRY);
        $customerMock->method('getPhoneNumbers')
            ->willReturn(['+5511912345678']);
        $customerMock->method('getEmail')
            ->willReturn(self::CUSTOMER_EMAIL);
        $customerMock->method('getDocumentNumber')
            ->willReturn(self::CUSTOMER_DOCUMENTNUMBER);

        return $customerMock;
    }

    private function getConfiguredCustomerWithoutDocumentsMockForPayloadTest()
    {
        $customerMock = $this->getConfiguredCustomerGenericMockForPayloadTest();

        $customerMock->method('getDocuments')
            ->willReturn(null);

        return $customerMock;
    }

    private function getConfiguredCustomerMockForPayloadTest()
    {
        $documentsMock = $this->getConfiguredDocumentsMockForPayloadTest();

        $customerMock = $this->getConfiguredCustomerGenericMockForPayloadTest();

        $customerMock->method('getDocuments')
            ->willReturn($documentsMock);

        return $customerMock;
    }

    private function getConfiguredDocumentsMockForPayloadTest()
    {
        $documentsMock = $this->getMockBuilder('PagarMe\Sdk\Customer\Document\Document')
            ->disableOriginalConstructor()
            ->getMock();

        $documentsMock->method('getType')->willReturn(self::CUSTOMER_DOCUMENTTYPE);
        $documentsMock->method('getNumber')->willReturn(self::CUSTOMER_DOCUMENTNUMBER);

        return [ $documentsMock ];
    }

    private function getConfiguredSplitRuleMockForPayloadTest($recipientId, $percentage)
    {
        $splitRule = $this->getMockBuilder('PagarMe\Sdk\SplitRule\SplitRule')
            ->disableOriginalConstructor()->getMock();

        $splitRule->method('getRecipient')
            ->willReturn(new Recipient(['id' => $recipientId]));
        $splitRule->method('getChargeProcessingFee')
            ->willReturn(true);
        $splitRule->method('getChargeRemainder')
            ->willReturn(true);
        $splitRule->method('getLiable')
            ->willReturn(true);

        if ($percentage) {
            $splitRule->method('getPercentage')
                ->willReturn(self::SPLIT_RULE_VALUE);
            $splitRule->method('getAmount')
                ->willReturn(null);
        } else {
            $splitRule->method('getAmount')
                ->willReturn(self::SPLIT_RULE_VALUE);
        }

        return $splitRule;
    }

    private function getConfiguredSplitRuleCollectionMockForPayloadTest($percentage = true)
    {
        $rules = new SplitRuleCollection();
        $rules[] = $this->getConfiguredSplitRuleMockForPayloadTest(self::SPLIT_RULE_RECIPIENT_ID_1, $percentage);
        $rules[] = $this->getConfiguredSplitRuleMockForPayloadTest(self::SPLIT_RULE_RECIPIENT_ID_2, $percentage);

        return $rules;
    }

    private function getExpectedPayloadWithSplitRulesAmount()
    {
        return array_merge(
            $this->getDefaultPayload(),
            ["split_rules" => [
                    [
                        "recipient_id"          => self::SPLIT_RULE_RECIPIENT_ID_1,
                        "amount"                => self::SPLIT_RULE_VALUE,
                        "liable"                => true,
                        "charge_processing_fee" => true,
                        "charge_remainder_fee"  => true
                    ],
                    [
                        "recipient_id"          => self::SPLIT_RULE_RECIPIENT_ID_2,
                        "amount"                => 50,
                        "liable"                => true,
                        "charge_processing_fee" => true,
                        "charge_remainder_fee"  => true
                    ]
                ]
            ]
        );
    }

    private function getExpectedPayloadWithSplitRulesPercentage()
    {
        return array_merge(
            $this->getDefaultPayload(),
            ["split_rules" => [
                    [
                        "recipient_id"          => self::SPLIT_RULE_RECIPIENT_ID_1,
                        "percentage"            => self::SPLIT_RULE_VALUE,
                        "liable"                => true,
                        "charge_processing_fee" => true,
                        "charge_remainder_fee"  => true
                    ],
                    [
                        "recipient_id"          => self::SPLIT_RULE_RECIPIENT_ID_2,
                        "percentage"            => 50,
                        "liable"                => true,
                        "charge_processing_fee" => true,
                        "charge_remainder_fee"  => true
                    ]
                ]
            ]
        );
    }

    private function getDefaultPayload()
    {
        return [
            'plan_id'        => self::PLAN_ID,
            'payment_method' => self::PLAN_PAYMENT_METHOD,
            'metadata'       => $this->planMetadata(),
            'customer'       => [
                'name'            => self::CUSTOMER_NAME,
                'external_id'     => self::CUSTOMER_EXTERNAL_ID,
                'type'            => self::CUSTOMER_TYPE,
                'country'         => self::CUSTOMER_COUNTRY,
                'phone_numbers'   => ['+5511912345678'],
                'email'           => self::CUSTOMER_EMAIL,
                'documents'       => [[
                    'type' => self::CUSTOMER_DOCUMENTTYPE,
                    'number' => self::CUSTOMER_DOCUMENTNUMBER
                ]]
            ],
            'postback_url' => self::POSTBACK_URL
        ];
    }

    private function getDefaultPayloadWithoutDocuments()
    {
        $payload = $this->getDefaultPayload();
        $payload['customer']['documents'] = [];
        return $payload;
    }

    /**
     * @test
     */
    public function mustPayloadBeCorrect()
    {
        $planMock = $this->getMockBuilder('PagarMe\Sdk\Plan\Plan')
            ->disableOriginalConstructor()
            ->getMock();
        $planMock->method('getId')->willReturn(self::PLAN_ID);


        $customerMock = $this->getConfiguredCustomerMockForPayloadTest();

        $boletoSubscriptionCreateRequest = new BoletoSubscriptionCreate(
            $planMock,
            $customerMock,
            self::POSTBACK_URL,
            $this->planMetadata(),
            [],
            []
        );

        $this->assertEquals(
            $boletoSubscriptionCreateRequest->getPayload(),
            $this->getDefaultPayload()
        );
    }

    /**
     * @test
     */
    public function mustPayloadWithSplitRuleAmountBeCorrect()
    {
        $planMock = $this->getMockBuilder('PagarMe\Sdk\Plan\Plan')
            ->disableOriginalConstructor()
            ->getMock();
        $planMock->method('getId')->willReturn(self::PLAN_ID);

        $customerMock = $this->getConfiguredCustomerMockForPayloadTest();

        $boletoSubscriptionCreateRequest = new BoletoSubscriptionCreate(
            $planMock,
            $customerMock,
            self::POSTBACK_URL,
            $this->planMetadata(),
            ['split_rules' => $this->getConfiguredSplitRuleCollectionMockForPayloadTest(false)]
        );

        $this->assertEquals(
            $boletoSubscriptionCreateRequest->getPayload(),
            $this->getExpectedPayloadWithSplitRulesAmount()
        );
    }

    /**
     * @test
     */
    public function mustPayloadWithSplitRulePercentageBeCorrect()
    {
        $planMock = $this->getMockBuilder('PagarMe\Sdk\Plan\Plan')
            ->disableOriginalConstructor()
            ->getMock();
        $planMock->method('getId')->willReturn(self::PLAN_ID);

        $customerMock = $this->getConfiguredCustomerMockForPayloadTest();

        $boletoSubscriptionCreateRequest = new BoletoSubscriptionCreate(
            $planMock,
            $customerMock,
            self::POSTBACK_URL,
            $this->planMetadata(),
            ['split_rules' => $this->getConfiguredSplitRuleCollectionMockForPayloadTest()]
        );

        $this->assertEquals(
            $boletoSubscriptionCreateRequest->getPayload(),
            $this->getExpectedPayloadWithSplitRulesPercentage()
        );
    }

    /**
     * @test
     */
    public function mustPayloadWithoutDocumentsBeCorrect()
    {
        $planMock = $this->getMockBuilder('PagarMe\Sdk\Plan\Plan')
            ->disableOriginalConstructor()
            ->getMock();
        $planMock->method('getId')->willReturn(self::PLAN_ID);


        $customerMock = $this->getConfiguredCustomerWithoutDocumentsMockForPayloadTest();

        $boletoSubscriptionCreateRequest = new BoletoSubscriptionCreate(
            $planMock,
            $customerMock,
            self::POSTBACK_URL,
            $this->planMetadata(),
            [],
            []
        );

        $this->assertEquals(
            $boletoSubscriptionCreateRequest->getPayload(),
            $this->getDefaultPayloadWithoutDocuments()
        );
    }

    private function planMetadata()
    {
        return [
            'foo' => 'bar',
            'a'   => 'b'
        ];
    }

    /**
     * @test
     */
    public function mustMethodBeCorrect()
    {
        $planMock = $this->getMockBuilder('PagarMe\Sdk\Plan\Plan')
            ->disableOriginalConstructor()
            ->getMock();

        $customerMock = $this->getMockBuilder('PagarMe\Sdk\Customer\Customer')
            ->disableOriginalConstructor()
            ->getMock();

        $boletoSubscriptionCreateRequest = new BoletoSubscriptionCreate(
            $planMock,
            $customerMock,
            null,
            [],
            [],
            []
        );

        $this->assertEquals(
            $boletoSubscriptionCreateRequest->getMethod(),
            RequestInterface::HTTP_POST
        );
    }

    /**
     * @test
     */
    public function mustPathBeCorrect()
    {
        $planMock = $this->getMockBuilder('PagarMe\Sdk\Plan\Plan')
            ->disableOriginalConstructor()
            ->getMock();

        $customerMock = $this->getMockBuilder('PagarMe\Sdk\Customer\Customer')
            ->disableOriginalConstructor()
            ->getMock();

        $boletoSubscriptionCreateRequest = new BoletoSubscriptionCreate(
            $planMock,
            $customerMock,
            null,
            [],
            [],
            []
        );

        $this->assertEquals(
            $boletoSubscriptionCreateRequest->getPath(),
            self::PATH
        );
    }
}
