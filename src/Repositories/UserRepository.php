<?php

namespace Deputy\Repositories;

use Deputy\Utilities\Collection;
use Deputy\Contracts\Persistence\SimplePersistenceInterface;
use Deputy\Contracts\Repositories\UserRepositoryInterface;
use Deputy\Entities\User;
use Deputy\Exceptions\RecordNotFoundException;

/**
 * Class UserRepository
 * @package Deputy\Repositories
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * @var SimplePersistenceInterface
     */
    protected $persistence;

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * UserRepository constructor.
     *
     * @param SimplePersistenceInterface $persistence
     */
    public function __construct(SimplePersistenceInterface $persistence)
    {
        $this->persistence = $persistence;
    }

    /**
     * @inheritDoc
     */
    public function find(int $userId)
    {
        if (!$data = $this->persistence->retrieve($userId, $this->table)) {
            return null;
        }

        return User::draft($data);
    }

    /**
     * @inheritDoc
     */
    public function all(): Collection
    {
        $data = $this->persistence->get($this->table);

        return new Collection($this->entitized(array_values($data)));
    }

    /**
     * @inheritDoc
     */
    public function insert(User $user)
    {
        $this->persistence->insert($user->toArray(), $this->table);
    }

    /**
     * @inheritDoc
     */
    public function allInRoles(array $roleIds): Collection
    {
        $data = [];

        if ($roleIds) {
            // Since we use simple storage (non-SQL)
            // then we instead loop and filter the data to simulate a WHERE IN query in SQL
            $data = $this->persistence->get($this->table);
            $data = $this->filterOnlyWithRoles($data, $roleIds);
        }

        return new Collection($this->entitized(array_values($data)));
    }

    /**
     * This returns only the users that matches any given role ids
     *
     * @param array $data
     * @param array $roleIds
     * @return array
     */
    protected function filterOnlyWithRoles(array $data, array $roleIds)
    {
        return array_filter($data, function ($datum) use ($roleIds) {
            foreach ($roleIds as $key => $value) {
                if (!isset($datum['roleId']) || !in_array($datum['roleId'], $roleIds)) {
                    return false;
                }
            }

            return true;
        });
    }

    /**
     * Convert a raw array of users to an array of User entity objects
     *
     * @param array $data
     * @return array
     */
    protected function entitized(array $data): array
    {
        return array_map(function ($row) {
            return User::draft($row);
        }, $data);
    }
}