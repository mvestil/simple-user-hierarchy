<?php

namespace Deputy\Services;

use Deputy\Contracts\Repositories\RoleRepositoryInterface;
use Deputy\Contracts\Services\RoleServiceInterface;
use Deputy\Entities\Role;
use Deputy\Exceptions\ValidationException;
use Deputy\Utilities\Collection;
use Deputy\Utilities\Validator;

/**
 * Class RoleService
 * @package Deputy\Services
 */
class RoleService implements RoleServiceInterface
{
    /**
     * @var RoleRepositoryInterface
     */
    protected $role;

    /**
     * RoleService constructor.
     *
     * @param RoleRepositoryInterface $role
     */
    public function __construct(RoleRepositoryInterface $role)
    {
        $this->role = $role;
    }

    /**
     * @inheritDoc
     */
    public function all(): Collection
    {
        return $this->role->all();
    }

    /**
     * @inheritDoc
     *
     * @throws ValidationException
     */
    public function store($data)
    {
        Validator::required($data, ['Id', 'Name', 'Parent'], true);

        foreach ($data as $datum) {
            $role = Role::draft([
                'id' => $datum['Id'],
                'name' => $datum['Name'],
                'parentId' => $datum['Parent'],
            ]);

            $this->throwIfParentIsItself($role);
            $this->throwIfInfiniteRelation($role);

            $this->role->insert($role);
        }
    }

    /**
     * Throw exception of role's parent is itself
     *
     * @param Role $role
     * @throws ValidationException
     */
    protected function throwIfParentIsItself(Role $role)
    {
        if ($role->id == $role->parentId) {
            throw new ValidationException('A role cannot be a parent of itself.');
        }
    }

    /**
     * Throw exception if if a role's parent is child of the given role that causes infinite relationship
     *
     * @param Role $role
     * @throws ValidationException
     */
    protected function throwIfInfiniteRelation(Role $role)
    {
        $parent = $this->role->find($role->parentId);

        if ($parent->parentId == $role->id) {
            throw new ValidationException('Infinite parent-child relationship detected.');
        }
    }
}