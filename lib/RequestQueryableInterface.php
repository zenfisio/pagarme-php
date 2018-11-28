<?php

namespace PagarMe\Sdk;

interface RequestQueryableInterface
{
    /**
     * @param array $query
     */
    public function setQuery($query);

    /**
     * @return array
     */
    public function getQuery();
}