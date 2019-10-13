<?php

namespace Deputy\Repositories;

use Deputy\Contracts\Persistence\SimplePersistenceInterface;
use Deputy\Contracts\Repositories\RoleRepositoryInterface;
use Deputy\Exceptions\RecordNotFoundException;
use Deputy\Utilities\Collection;
use Deputy\Entities\Role;

/**
 * Class RoleRepository
 * @package Deputy\Repositories
 */
class RoleRepository implements RoleRepositoryInterface
{
    /**
     * @var SimplePersistenceInterface
     */
    protected $persistence;

    /**
     * @var string
     */
    protected $table = 'roles';

    /**
     * RoleRepository constructor.
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
    public function find(int $roleId)
    {
        if (!$data = $this->persistence->retrieve($roleId, $this->table)) {
            return null;
        }

        return Role::draft($data);
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
    public function insert(Role $role)
    {
        $this->persistence->insert($role->toArray(), $this->table);
    }

    /**
     * Returns the children and children's children of a parent
     *
     * @param int $roleId
     * @return Collection
     */
    public function childrenOf(int $roleId): Collection
    {
        $children = [];

        if ($data = $this->persistence->get($this->table)) {
            $parentRole = $this->find($roleId);
            $children = $this->findChildren($this->buildDictionary($data), $parentRole);
        }

        return new Collection($children);
    }

    /**
     * Build a dictionary with parent_id as a key and with values as the children
     * i.e
     *  $dictionary[parent1_id] = [child1, child2]
     *  $dictionary[child2_id] = [child3, child4]
     *
     * The above means parent1_id has 2 children - child1 and child2.
     * While child2 (which is a child of parent1_id) has 2 children child3 and child4
     *
     * @param array $data
     * @return array
     */
    private function buildDictionary(array $data): array
    {
        $dictionary = [];

        foreach ($data as $role) {
            $dictionary[$role['parentId']][] = Role::draft($role);
        }

        // remove the role without a parent, in this case, with index=0
        unset($dictionary[0]);

        return $dictionary;
    }

    /**
     * Find the children of a given role in a recursive way using a dictionary
     *
     * @param array $dictionary
     * @param Role $parentRole
     * @return array
     */
    private function findChildren(array $dictionary, Role $parentRole): array
    {
        $children = [];

        if (!isset($dictionary[$parentRole->id])) {
            return $children;
        }

        foreach ($dictionary[$parentRole->id] as $childRole) {
            $children[] = $childRole;
            $children = array_merge($children, $this->findChildren($dictionary, $childRole));
        }

        return $children;
    }

    /**
     * Convert a raw array of users to an array of Role entity objects
     *
     * @param array $data
     * @return array
     */
    protected function entitized(array $data): array
    {
        return array_map(function ($row) {
            return Role::draft($row);
        }, $data);
    }
}