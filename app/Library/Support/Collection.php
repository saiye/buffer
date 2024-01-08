<?php

declare(strict_types=1);


namespace App\Library\Support;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

class Collection implements ArrayAccess, IteratorAggregate
{
    protected $items = [];

    public function __construct($items = [])
    {
        $this->items = $this->getArrayableItems($items);
    }

    public function getArrayableItems($items)
    {
        if (is_array($items)) {
            return $items;
        } elseif ($items instanceof Traversable) {
            return iterator_to_array($items);
        }
        return (array)$items;
    }


    public static function range($from, $to)
    {
        return new static(range($from, $to));
    }

    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->items[$offset] ?? null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        if (isset($this->items[$offset])) {
            unset($this->items[$offset]);
        }
    }

    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    protected function useAsCallable($value)
    {
        return !is_string($value) && is_callable($value);
    }


    protected function valueRetriever($value)
    {
        if ($this->useAsCallable($value)) {
            return $value;
        }

        return function ($item) use ($value) {
            return Arr::dataGet($item, $value);
        };
    }

    public function keyBy($keyBy)
    {
        $keyBy = $this->valueRetriever($keyBy);

        $results = [];

        foreach ($this->items as $key => $item) {
            $resolvedKey = $keyBy($item, $key);

            if (is_object($resolvedKey)) {
                $resolvedKey = (string)$resolvedKey;
            }

            $results[$resolvedKey] = $item;
        }

        return new static($results);
    }

    public function toArray()
    {
        return $this->all();
    }

    public function all()
    {
        return $this->items;
    }
    public function __get($name)
    {
      return $this->items[$name]??null;
    }
}
