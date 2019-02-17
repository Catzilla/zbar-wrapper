<?php

namespace Catzilla\ZBarWrapper;

use Catzilla\ZBarWrapper\ZBarResult;

class ZBarResultCollection implements \ArrayAccess, \Iterator
{
    private $results;

    function __construct($result)
    {
        $results = explode(PHP_EOL, trim($result));

        $this->results = [];

        foreach ($results as $result) {
            array_push($this->results, new ZBarResult($result));
        }
    }

    /**
     * Returns first result from collection
     *
     * @return Catzilla\ZBarWrapper\ZBarResult
     */
    public function first()
    {
        return reset($this->results);
    }

    /**
     * Returns last result from collection
     *
     * @return Catzilla\ZBarWrapper\ZBarResult
     */
    public function last()
    {
        return end($this->results);
    }

    /* ArrayAccess */

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->results[] = $value;
        } else {
            $this->results[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->results[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->results[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->results[$offset]) ? $this->results[$offset] : null;
    }

    /* Iterator */

    private $position = 0;

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->results[$this->position];
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
        return isset($this->results[$this->position]);
    }
}
