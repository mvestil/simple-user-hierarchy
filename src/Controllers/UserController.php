<?php

namespace Deputy\Controllers;

use Deputy\Contracts\Services\UserServiceInterface;

/**
 * Class UserController
 * @package Deputy\Controllers
 */
class UserController
{
    /**
     * @var UserServiceInterface
     */
    protected $service;

    /**
     * UserController constructor.
     * @param UserServiceInterface $service
     */
    public function __construct(UserServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Returns all users
     *
     * @return \Deputy\Utilities\Collection
     */
    public function index()
    {
        return $this->service->all();
    }


    /**
     * Store user records
     *
     * @param $request
     */
    public function store($request)
    {
        $this->service->store($request);
    }

    /**
     * Returns the subordinates of the user
     *
     * @param int $userId
     * @return \Deputy\Utilities\Collection|mixed
     */
    public function subordinates(int $userId)
    {
        $subordinates = $this->service->getSubordinates($userId);

        // TODO : Return collection
        return $subordinates;
    }
}