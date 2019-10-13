<?php
namespace Deputy\Contracts\Repositories;

use Deputy\Utilities\Collection;
use Deputy\Entities\Role;

/**
 * Interface RoleRepositoryInterface
 * @package Deputy\Contracts\Repositories
 */
interface RoleRepositoryInterface
{
    /**
     * Find a role record
     *
     * @param int $roleId
     * @return Role|null
     */
    public function find(int $roleId);

    /**
     * Returns all roles
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Insert role records
     *
     * @param Role $role
     * @return mixed
     */
    public function insert(Role $role);

    /**
     * Returns all children including children's children of a given role id
     *
     * @param int $roleId
     * @return Collection
     */
    public function childrenOf(int $roleId): Collection;
}