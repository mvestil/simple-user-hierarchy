<?php

namespace Deputy\Persistence;

use Deputy\Contracts\Persistence\SimplePersistenceInterface;
use Deputy\Exceptions\StorageException;

/**
 * Class InMemoryPersistence
 *
 * This is a simple storage in memory that supports only basic storing/retrieving data.
 *
 * @package Deputy\Persistence
 */
class InMemoryPersistence implements SimplePersistenceInterface
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @inheritDoc
     */
    public function retrieve(int $id, string $table)
    {
        return $this->data[$table][$id] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function insert(array $data, string $table)
    {
        if (isset($this->data[$table][$data['id']])) {
            throw new StorageException('Primary key already exist');
        }
        $this->data[$table][$data['id']] = $data;
    }

    /**
     * @inheritDoc
     */
    public function get(string $table)
    {
        return isset($this->data[$table]) ? array_values($this->data[$table]) : null;
    }
}