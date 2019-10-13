<?php

namespace Deputy\Controllers;

use Deputy\Contracts\Services\RoleServiceInterface;

/**
 * Class RoleController
 * @package Deputy\Controllers
 */
class RoleController
{
    /**
     * @var RoleServiceInterface
     */
    protected $service;

    /**
     * RoleController constructor.
     * @param RoleServiceInterface $role
     */
    public function __construct(RoleServiceInterface $role)
    {
        $this->service = $role;
    }

    /**
     * Returns all roles
     *
     * @return \Deputy\Utilities\Collection
     */
    public function index()
    {
        return $this->service->all();
    }

    /**
     * Store roles
     *
     * @param $request
     */
    public function store($request)
    {
        $this->service->store($request);
    }
}