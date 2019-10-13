<?php
namespace Deputy\Contracts\Services;

use Deputy\Utilities\Collection;

/**
 * Interface RoleServiceInterface
 * @package Deputy\Contracts\Services
 */
interface RoleServiceInterface
{
    /**
     * Handles business logic for returning all roles
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Handles business logic for storing role records
     *
     * @param array $data
     * @return mixed
     */
    public function store($data);
}