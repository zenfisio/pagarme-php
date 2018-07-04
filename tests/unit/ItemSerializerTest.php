<?php

namespace PagarMe\SdkTest;

use PagarMe\Sdk\Item\Item;
use PagarMe\Sdk\Item\ItemCollection;

class ItemSerializerTest extends \PHPUnit_Framework_TestCase
{
    use \PagarMe\Sdk\ItemSerializer;

    /**
     * @test
     */
    public function mustSerializeItem()
    {
        $items = new ItemCollection();
        $item1 = new Item([
            "id" => "1",
            "title" => "Item 1",
            "unit_price" => 500,
            "quantity" => 2,
            "tangible" => true
        ]);

        $item2 = new Item([
            "id" => "2",
            "title" => "Item 2",
            "unit_price" => 1000,
            "quantity" => 1,
            "tangible" => true
        ]);

        $items[] = $item1;
        $items[] = $item2;

        $this->assertEquals(
            [
                array(
                    "id" => "1",
                    "title" => "Item 1",
                    "unit_price" => 500,
                    "quantity" => 2,
                    "tangible" => true
                ),
                array(
                    "id" => "2",
                    "title" => "Item 2",
                    "unit_price" => 1000,
                    "quantity" => 1,
                    "tangible" => true
                )
            ],
            $this->getItemsInfo($items)
        );
    }
}
