<?php

namespace PagarMe\Sdk\Customer\Document;

class DocumentCollection implements \ArrayAccess, \Iterator, \Countable
{

    /**
     * @var array $documents
     */
    private $documents = [];

    /**
     * @var int $position
     */
    private $position = 0;

    public function offsetSet($offset, $value)
    {
        if (!$value instanceof Document) {
            throw new \InvalidArgumentException(
                "The value supplied is not a Document object",
                1
            );
        }

        if (is_null($offset)) {
            $this->documents[] = $value;
        } else {
            $this->documents[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->documents[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->documents[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->documents[$offset]) ? $this->documents[$offset] : null;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->documents[$this->position];
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
        return isset($this->documents[$this->position]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->documents);
    }
}
