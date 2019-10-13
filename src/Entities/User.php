<?php

namespace Deputy\Entities;

/**
 * Class User
 */
class User extends AbstractEntity
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $roleId;

    /**
     * User constructor.
     *
     * @param $id
     * @param $name
     * @param $roleId
     */
    private function __construct(int $id, string $name, int $roleId)
    {
        $this->id     = $id;
        $this->name   = $name;
        $this->roleId = $roleId;
    }

    /**
     * @param array $data
     * @return User
     */
    public static function draft(array $data): User
    {
        return new static($data['id'], $data['name'], $data['roleId']);
    }
}