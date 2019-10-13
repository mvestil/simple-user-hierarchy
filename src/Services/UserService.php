<?php

namespace Deputy\Services;

use Deputy\Exceptions\RecordNotFoundException;
use Deputy\Exceptions\ValidationException;
use Deputy\Utilities\Collection;
use Deputy\Contracts\Repositories\RoleRepositoryInterface;
use Deputy\Contracts\Repositories\UserRepositoryInterface;
use Deputy\Contracts\Services\UserServiceInterface;
use Deputy\Entities\User;
use Deputy\Utilities\Validator;

/**
 * Class UserService
 * @package Deputy\Services
 */
class UserService implements UserServiceInterface
{

    /**
     * @var UserRepositoryInterface
     */
    protected $user;

    /**
     * @var RoleRepositoryInterface
     */
    protected $role;

    /**
     * UserService constructor.
     * @param UserRepositoryInterface $user
     * @param RoleRepositoryInterface $role
     */
    public function __construct(UserRepositoryInterface $user, RoleRepositoryInterface $role)
    {
        $this->user = $user;
        $this->role = $role;
    }

    /**
     * @inheritDoc
     */
    public function all(): Collection
    {
        return $this->user->all();
    }

    /**
     * @inheritDoc
     *
     * @throws ValidationException
     */
    public function store($data)
    {
        Validator::required($data, ['Id', 'Name', 'Role'], true);

        foreach ($data as $datum) {
            $this->user->insert(User::draft([
                'id' => $datum['Id'],
                'name' => $datum['Name'],
                'roleId' => $datum['Role'],
            ]));
        }
    }


    /**
     * @inheritDoc
     * @throws RecordNotFoundException
     */
    public function find(int $userId): User
    {
        if (!$user = $this->user->find($userId)) {
            throw new RecordNotFoundException('User record not found.');
        }

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function getSubordinates(int $userId): Collection
    {
        $user = $this->find($userId);
        $childRoles = $this->role->childrenOf($user->roleId);
        $childRolesId = $childRoles->pluck('id')->toArray();
        $subordinates = $this->user->allInRoles($childRolesId);

        return $subordinates;
    }
}