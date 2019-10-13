<?php
namespace Deputy\Entities;

/**
 * Class Role
 * @package Deputy\Entities
 */
class Role extends AbstractEntity
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
    public $parentId;

    /**
     * Role constructor.
     *
     * @param $id
     * @param $name
     * @param $parentId
     */
    private function __construct(int $id, string $name, int $parentId)
    {
        $this->id       = $id;
        $this->name     = $name;
        $this->parentId = $parentId;
    }

    /**
     * @param array $data
     * @return Role
     */
    public static function draft(array $data): Role
    {
        return new static($data['id'], $data['name'], $data['parentId']);
    }
}