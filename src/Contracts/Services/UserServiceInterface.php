<?php
namespace Deputy\Contracts\Services;

use Deputy\Utilities\Collection;
use Deputy\Entities\User;

/**
 * Interface UserServiceInterface
 * @package Deputy\Contracts\Services
 */
interface UserServiceInterface
{
    /**
     * Handles business for finding a user record
     *
     * @param int $userId
     * @return User
     */
    public function find(int $userId): User;

    /**
     * Handles business logic for storing user records
     *
     * @param array $data
     * @return mixed
     */
    public function store($data);

    /**
     * Handles business logic for returning user records
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Handles business logic for getting subordinates of users
     * including subordinates' subordinates
     *
     * @param int $userId
     * @return mixed
     */
    public function getSubordinates(int $userId): Collection;
}