<?php
namespace Deputy\Contracts\Repositories;

use Deputy\Utilities\Collection;
use Deputy\Entities\User;

/**
 * Interface UserRepositoryInterface
 * @package Deputy\Contracts\Repositories
 */
interface UserRepositoryInterface
{
    /**
     * Find a specific user
     *
     * @param int $userId
     * @return User|null
     */
    public function find(int $userId);

    /**
     * Get all users
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Insert user records
     *
     * @param User $user
     * @return mixed
     */
    public function insert(User $user);

    /**
     * Return all users that belongs in any given role ids
     *
     * @param array $roleIds
     * @return Collection
     */
    public function allInRoles(array $roleIds): Collection;
}