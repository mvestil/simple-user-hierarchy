<?php

namespace Deputy\Contracts\Persistence;

/**
 * Interface SimpleStorageInterface
 *
 * This is a simple storage interface supports only basic storing/retrieving data
 * and no complex query building like SQL
 */
interface SimplePersistenceInterface
{
    /**
     * Retrieve a single record in the storage
     *
     * @param int $id
     * @param string $table
     * @return array|null
     */
    public function retrieve(int $id, string $table);

    /**
     * Insert records in the storage
     *
     * @param array $data
     * @param string $table
     * @return mixed
     */
    public function insert(array $data, string $table);

    /**
     * Get records in the storage
     *
     * @param string $table
     * @return array|null
     */
    public function get(string $table);
}
