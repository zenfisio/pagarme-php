<?php

namespace PagarMe\SdkTest\Item;

use PagarMe\Sdk\Item\ItemBuilder;

class ItemBuilderTest extends \PHPUnit_Framework_TestCase
{
    use \PagarMe\Sdk\Item\ItemBuilder;

    /**
     * @test
     */
    public function mustItemCollectionCreatedCorrectly()
    {
        $itemCollection = $this->buildItems(json_decode('[{"object":"item","id":"123","title":"item 1","unit_price":500,"quantity":1,"tangible":true},{"object":"item","id":"1234","title":"item 2","unit_price":1000,"quantity":2,"tangible":false}]'));

        $this->assertInstanceOf(
            'PagarMe\Sdk\Item\ItemCollection',
            $itemCollection
        );

        $this->assertInstanceOf(
            'PagarMe\Sdk\Item\Item',
            $itemCollection[0]
        );
        $this->assertInstanceOf(
            'PagarMe\Sdk\Item\Item',
            $itemCollection[1]
        );

        $this->assertInstanceOf('PagarMe\Sdk\Item\Item', $itemCollection[0]);
        $this->assertInstanceOf('PagarMe\Sdk\Item\Item', $itemCollection[1]);
    }
}
