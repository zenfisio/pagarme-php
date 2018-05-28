<?php

namespace PagarMe\Sdk\Subscription\Request;

use PagarMe\Sdk\RequestInterface;
use PagarMe\Sdk\Plan\Plan;
use PagarMe\Sdk\Customer\Customer;
use PagarMe\Sdk\SplitRule\SplitRuleCollection;

abstract class SubscriptionCreate implements RequestInterface
{
    /**
     * @var Plan $plan
     */
    protected $plan;

    /**
     * @var Customer $customer
     */
    protected $customer;

    /**
     * @var string $postbackUrl
     */
    protected $postbackUrl;

    /**
     * @var array $metadata
     */
    protected $metadata;

    /**
     * @var string $paymentMethod
     */
    protected $paymentMethod;

    /**
     * @var array $extraAttributes
     */
    protected $extraAttributes;

    /**
     * @var Plan $plan
     * @var Customer $customer
     * @var string $postbackUrl
     * @var array $metadata
     */
    public function __construct(
        Plan $plan,
        Customer $customer,
        $postbackUrl,
        $metadata,
        $extraAttributes
    ) {
        $this->plan            = $plan;
        $this->customer        = $customer;
        $this->postbackUrl     = $postbackUrl;
        $this->metadata        = $metadata;
        $this->extraAttributes = $extraAttributes;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        $payload = [
            'plan_id'        => $this->plan->getId(),
            'payment_method' => $this->paymentMethod,
            'metadata'       => $this->metadata,
            'customer'       => [
                'name'            => $this->customer->getName(),
                'email'           => $this->customer->getEmail(),
                'external_id'     => $this->customer->getExternalId(),
                'type'            => $this->customer->getType(),
                'country'         => $this->customer->getCountry(),
                'phone_numbers'   => $this->customer->getPhoneNumbers(),
                'documents'       => $this->getDocumentsData()
            ],
            'postback_url' => $this->postbackUrl
        ];

        if (!is_null($this->customer->getId())) {
            $payload['customer']['id'] = $this->customer->getId();
        }

        if (array_key_exists('split_rules', $this->extraAttributes)
            && $this->extraAttributes['split_rules'] instanceof SplitRuleCollection) {
            $payload['split_rules'] = $this->getSplitRulesInfo();
        }

        return $payload;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return 'subscriptions';
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_POST;
    }

    /**
     *  @return array
     */
    protected function getDocumentsData()
    {
        $documents = $this->customer->getDocuments();

        if (is_null($documents)) {
            return [];
        }

        $documentsData = array_map(function ($document) {
            return [
                'type' => $document->getType(),
                'number' => $document->getNumber()
            ];
        }, $documents);

        return $documentsData;
    }

    /**
     * @param \PagarMe\Sdk\SplitRule\SplitRuleCollection $splitRules
     * @return array
     */
    private function getSplitRulesInfo()
    {
        $rules = [];

        foreach ($this->extraAttributes['split_rules'] as $key => $splitRule) {
            $rule = [
                'recipient_id'          => $splitRule->getRecipient()->getId(),
                'charge_processing_fee' => $splitRule->getChargeProcessingFee(),
                'charge_remainder_fee' => $splitRule->getChargeRemainder(),
                'liable'                => $splitRule->getLiable()
            ];

            $rules[$key] = array_merge($rule, $this->getRuleValue($splitRule));
        }

        return $rules;
    }

    /**
     * @param \PagarMe\Sdk\SplitRule\SplitRule $splitRule
     * @return array
     */
    private function getRuleValue($splitRule)
    {
        if (is_null($splitRule->getAmount())) {
            return ['percentage' => $splitRule->getPercentage()];
        }

        return ['amount' => $splitRule->getAmount()];
    }
}
