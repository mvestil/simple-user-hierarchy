<?php

namespace Deputy\Contracts;

/**
 * Interface Arrayable
 * @package Deputy\Contracts
 */
interface Arrayable
{
    /**
     * @return mixed
     */
    public function toArray();
}