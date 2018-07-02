<?php

namespace PagarMe\Sdk\Item;

class Item
{
    use \PagarMe\Sdk\Fillable;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $unitPrice;

    /**
     * @var string
     */
    private $quantity;

    /**
     * @var string
     */
    private $tangible;

    /**
     * @param array $arrayData
     */
    public function __construct($arrayData)
    {
        $this->fill($arrayData);
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getTangible()
    {
        return $this->tangible;
    }
}
