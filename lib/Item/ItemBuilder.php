<?php

namespace PagarMe\Sdk\Item;

use PagarMe\Sdk\Item\Item;

trait ItemBuilder
{
    /**
     * @param array $ItemData
     * @return ItemCollection
     */
    private function buildItems($ItemData)
    {
        $items = new ItemCollection();

        if (is_array($ItemData)) {
            foreach ($ItemData as $item) {
                $items[] = new Item(get_object_vars($item));
            }
        }

        return $items;
    }
}
