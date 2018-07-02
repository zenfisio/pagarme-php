<?php

namespace PagarMe\Sdk;

use PagarMe\Sdk\Item\ItemCollection;

trait ItemSerializer
{

    /**
     * @param \PagarMe\Sdk\Item\ItemCollection $items
     * @return array
     */
    public function getItemsInfo(ItemCollection $itemsCollection)
    {
        $items = [];

        foreach ($itemsCollection as $key => $item) {
            $item = [
                'id'          => $item->getId(),
                'title'       => $item->getTitle(),
                'unit_price'  => $item->getUnitPrice(),
                'quantity'    => $item->getQuantity(),
                'tangible'    => $item->getTangible()
            ];

            $items[$key] = $item;
        }

        return $items;
    }
}
