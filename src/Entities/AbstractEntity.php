<?php

namespace Deputy\Entities;

use Deputy\Contracts\Arrayable;

/**
 * Class AbstractEntity
 * @package Deputy\Entities
 */
abstract class AbstractEntity implements Arrayable
{
    /**
     * Build and return an entity object
     *
     * @param array $data
     * @return static
     */
    abstract public static function draft(array $data);

    /**
     * Convert an entity to an array
     *
     * @return array
     */
    public function toArray()
    {
        return (array) $this;
    }
}