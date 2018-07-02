<?php

namespace PagarMe\Sdk\Item;

class ItemCollection implements \ArrayAccess, \Iterator, \Countable
{

    /**
     * @var array $items
     */
    private $items = [];

    /**
     * @var int $position
     */
    private $position = 0;

    public function offsetSet($offset, $value)
    {
        if (!$value instanceof Item) {
            throw new \InvalidArgumentException(
                "The value supplied is not a Item checkoutobject",
                1
            );
        }

        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->items[$offset]) ? $this->items[$offset] : null;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->items[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->items[$this->position]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }
}
