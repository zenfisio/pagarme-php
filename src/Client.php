<?php

namespace PagarMe;

use Transaction\Transaction;

class Client
{
    private $baseUrl = 'https://api.pagar.me:443/1';

    private $apiKey;

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }
}

