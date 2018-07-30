<?php

namespace PagarMe;

use Transaction\Transaction;

class Client
{
    private $baseUrl = 'https://api.pagar.me:443/1';

    private $body = [];

    public function __construct($apiKey) {
        if (!validateAuthentication($apiKey)) {
            throw Exception('You must supply a valid Api Key');
        }

        $this->bindToBody($apiKey);
    }

    private function bindToBody($apiKey) {
        return array_merge($body, ['apiKey' => $apiKey]);
    }

    private function validateAuthentication($apiKey) {
        if (empty($apiKey)) {
            return false;
        }

        return true;
    }
}

