<?php

namespace PagarMe\Endpoints;

interface EndpointInterface
{
    public $uri;

    public $method;

    const POST = 'POST';
    const GET = 'GET';
    const PUT = 'PUT';
    const DELETE = 'DELETE';
}
