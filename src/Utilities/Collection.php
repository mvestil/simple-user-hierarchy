<?php

namespace Deputy\Utilities;

use ArrayIterator;
use Deputy\Contracts\Arrayable;
use IteratorAggregate;
use Countable;

/**
 * Class Collection
 * @package Deputy\Utilities
 */
class Collection implements Countable, IteratorAggregate, Arrayable
{
    /**
     * @var array
     */
    protected $items;

    /**
     * Collection constructor.
     *
     * @param $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @return ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Returns a new collection with only the specific column
     *
     * @param $column
     * @return $this
     */
    public function pluck($column): self
    {
        return new self(array_column($this->items, $column));
    }

    /**
     * Returns the first of the collection
     *
     * @return mixed|null
     */
    public function first()
    {
        return $this->items[0] ?? null;
    }

    /**
     * Convert the collection into an arary
     *
     * @return array
     */
    public function toArray(): array
    {
        return array_map(function ($item) {
            return $item instanceof Arrayable ? $item->toArray() : $item;
        }, $this->items);
    }

    /**
     * Convert the collection into json encoded
     *
     * @return false|string
     */
    public function toJson()
    {
        return json_encode($this->items);
    }
}