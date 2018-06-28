<?php

namespace PagarMe\Sdk\Transaction\Request;

use PagarMe\Sdk\RequestInterface;
use PagarMe\Sdk\Transaction\Transaction;
use PagarMe\Sdk\SplitRule\SplitRuleCollection;
use PagarMe\Sdk\Customer\Customer;

class TransactionCreate implements RequestInterface
{
    use \PagarMe\Sdk\SplitRuleSerializer;

    /**
     * @var \PagarMe\Sdk\Transaction\Transaction
     */
    protected $transaction;

    /**
     * @param \PagarMe\Sdk\Transaction\Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        $customer = $this->transaction->getCustomer();
        $billing  = $this->transaction->getBilling();
        $shipping  = $this->transaction->getShipping();

        $transactionData = [
            'amount'         => $this->transaction->getAmount(),
            'payment_method' => $this->transaction->getPaymentMethod(),
            'postback_url'   => $this->transaction->getPostbackUrl(),
            'metadata' => $this->transaction->getMetadata()
        ];

        $customerData = [
            'name'            => $customer->getName(),
            'external_id'     => $customer->getExternalId(),
            'type'            => $customer->getType(),
            'country'         => $customer->getCountry(),
            'phone_numbers'   => $customer->getPhoneNumbers(),
            'documents'       => $customer->getDocuments(),
            'email'           => $customer->getEmail()
        ];


        if (!is_null($customer->getId())) {
            $customerData['id'] = $customer->getId();
        }

        if (!is_null($billing)) {
            $address = $billing->getAddress();

            $billingData = [
                'name'    => $billing->getName(),
                'address' => [
                    'country'       => $address->getCountry(),
                    'state'         => $address->getState(),
                    'city'          => $address->getCity(),
                    'neighborhood'  => $address->getNeighborhood(),
                    'street'        => $address->getStreet(),
                    'street_number' => $address->getStreetNumber(),
                    'zipcode'       => $address->getZipcode()
                ]
            ];

            $transactionData['billing']  = $billingData;
        }

        if (!is_null($shipping)) {
            $address = $shipping->getAddress();

            $shippingData = [
                'name'    => $shipping->getName(),
                'fee'    => $shipping->getFee(),
                'delivery_date'    => $shipping->getDeliveryDate(),
                'expedited'    => $shipping->getExpedited(),
                'address' => [
                    'country'       => $address->getCountry(),
                    'state'         => $address->getState(),
                    'city'          => $address->getCity(),
                    'neighborhood'  => $address->getNeighborhood(),
                    'street'        => $address->getStreet(),
                    'street_number' => $address->getStreetNumber(),
                    'zipcode'       => $address->getZipcode()
                ]
            ];

            $transactionData['shipping']  = $shippingData;
        }

        $transactionData['customer'] = $customerData;

        if ($this->transaction->getSplitRules() instanceof SplitRuleCollection) {
            $transactionData['split_rules'] = $this->getSplitRulesInfo(
                $this->transaction->getSplitRules()
            );
        }

        return $transactionData;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return 'transactions';
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_POST;
    }
}
