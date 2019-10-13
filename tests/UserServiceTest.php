<?php

namespace Deputy\Tests;

use Deputy\Entities\User;
use Deputy\Exceptions\RecordNotFoundException;
use Deputy\Exceptions\StorageException;
use Deputy\Exceptions\ValidationException;
use Deputy\Persistence\InMemoryPersistence;
use Deputy\Repositories\RoleRepository;
use Deputy\Repositories\UserRepository;
use Deputy\Services\RoleService;
use Deputy\Services\UserService;
use Deputy\Utilities\Collection;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    public function testCanStoreUsers()
    {
        $userInput = json_decode('[{
                "Id": 1,
                "Name": "Adam Admin",
                "Role": 1
            },
            {
                "Id": 2,
                "Name": "Emily Employee",
                "Role": 4
            },
            {
                "Id": 3,
                "Name": "Sam Supervisor",
                "Role": 3
            },
            {
                "Id": 4,
                "Name": "Mary Manager",
                "Role": 2
            },
            {
                "Id":5,
                "Name": "Steve Trainer",
                "Role": 5
            }
         ]', true);

        $persistence = new InMemoryPersistence;
        $service = new UserService(
            new UserRepository($persistence),
            new RoleRepository($persistence)
        );

        $service->store($userInput);

        $collection = $service->all();
        $this->assertTrue($collection instanceof Collection);
        $this->assertEquals(5, $collection->count());
        $this->assertEquals(
            array_column($userInput, 'Name'),
            array_column($collection->toArray(), 'name')
        );
    }

    public function testInvalidStoreInput()
    {
        $persistence = new InMemoryPersistence;
        $service = new UserService(
            new UserRepository($persistence),
            new RoleRepository($persistence)
        );

         $userInput = null;

        try {
            $service->store($userInput);
        } catch(\Exception $e) {

        }

        $this->assertTrue($e instanceof ValidationException);
        $this->assertEquals('Missing parameters.', $e->getMessage());

        $userInput = json_decode('[{
                "Id": 1,
                "Role": 1
            },
            {
                "Id": 2,
                "Role": 4
            },
            {
                "Id": 3,
                "Role": 3
            },
            {
                "Id": 4,
                "Role": 2
            },
            {
                "Id":5,
                "Role": 5
            }
         ]', true);

        try {
            $service->store($userInput);
        } catch(\Exception $e) {

        }

        $this->assertTrue($e instanceof ValidationException);
        $this->assertEquals('Parameter Name is required field.', $e->getMessage());

        $userInput = json_decode('[{
                "Id": 1,
                "Name": "Adam Admin"
            },
            {
                "Id": 2,
                "Name": "Emily Employee",
                "Role": 4
            },
            {
                "Id": 3,
                "Name": "Sam Supervisor",
                "Role": 3
            },
            {
                "Id": 4,
                "Name": "Mary Manager",
                "Role": 2
            },
            {
                "Id":5,
                "Name": "Steve Trainer",
                "Role": 5
            }
         ]', true);

        try {
            $service->store($userInput);
        } catch(\Exception $e) {

        }

        $this->assertTrue($e instanceof ValidationException);
        $this->assertEquals('Parameter Role is required field.', $e->getMessage());
    }

    public function testCanFindUser()
    {
        $userInput = json_decode('[{
                "Id": 1,
                "Name": "Adam Admin",
                "Role": 1
            },
            {
                "Id": 2,
                "Name": "Emily Employee",
                "Role": 4
            },
            {
                "Id": 3,
                "Name": "Sam Supervisor",
                "Role": 3
            },
            {
                "Id": 4,
                "Name": "Mary Manager",
                "Role": 2
            },
            {
                "Id":5,
                "Name": "Steve Trainer",
                "Role": 5
            }
         ]', true);

        $persistence = new InMemoryPersistence;
        $service = new UserService(
            new UserRepository($persistence),
            new RoleRepository($persistence)
        );

        $service->store($userInput);

        $user = $service->find(1);
        $this->assertTrue($user instanceof User);
        $this->assertEquals(1, $user->id);
        $this->assertEquals('Adam Admin', $user->name);
        $this->assertEquals('1', $user->roleId);

        $user = $service->find(2);
        $this->assertTrue($user instanceof User);
        $this->assertEquals(2, $user->id);
        $this->assertEquals('Emily Employee', $user->name);
        $this->assertEquals('4', $user->roleId);
    }

    public function testCannotFindUser()
    {
        $userInput = json_decode('[{
                "Id": 1,
                "Name": "Adam Admin",
                "Role": 1
            },
            {
                "Id": 2,
                "Name": "Emily Employee",
                "Role": 4
            },
            {
                "Id": 3,
                "Name": "Sam Supervisor",
                "Role": 3
            },
            {
                "Id": 4,
                "Name": "Mary Manager",
                "Role": 2
            },
            {
                "Id":5,
                "Name": "Steve Trainer",
                "Role": 5
            }
         ]', true);

        $persistence = new InMemoryPersistence;
        $service = new UserService(
            new UserRepository($persistence),
            new RoleRepository($persistence)
        );

        $service->store($userInput);

        try {
            $user = $service->find(99999);
        } catch (\Exception $e) {

        }

        $this->assertTrue($e instanceof RecordNotFoundException);
        $this->assertEquals('User record not found.', $e->getMessage());
    }

    public function testGetAllSubordinates()
    {
        $userInput = json_decode('[{
                "Id": 1,
                "Name": "Adam Admin",
                "Role": 1
            },
            {
                "Id": 2,
                "Name": "Emily Employee",
                "Role": 4
            },
            {
                "Id": 3,
                "Name": "Sam Supervisor",
                "Role": 3
            },
            {
                "Id": 4,
                "Name": "Mary Manager",
                "Role": 2
            },
            {
                "Id":5,
                "Name": "Steve Trainer",
                "Role": 5
            }
         ]', true);
        $roleInput = json_decode('[{
                "Id": 1,
                "Name": "System Administrator",
                "Parent": 0
            },
            {
                "Id": 2,
                "Name": "Location Manager",
                "Parent": 1
            },
            {
                "Id": 3,
                "Name": "Supervisor",
                "Parent": 2
            },
            {
                "Id": 4,
                "Name": "Employee",
                "Parent": 3
            },
            {
                "Id": 5,
                "Name": "Trainer",
                "Parent": 3
            }
        ]', true);

        $persistence = new InMemoryPersistence;
        $userService = new UserService(
            new UserRepository($persistence),
            new RoleRepository($persistence)
        );
        $roleService = new RoleService(
            new RoleRepository($persistence)
        );

        $roleService->store($roleInput);
        $userService->store($userInput);

        $subordinates = $userService->getSubordinates(1);
        $this->assertEquals(4, $subordinates->count());

        $expected = ['Emily Employee', 'Sam Supervisor', 'Mary Manager', 'Steve Trainer'];
        $diff = array_diff($expected, $subordinates->pluck('name')->toArray());

        // assert if there's no diff between our expected and our actual result
        $this->assertEquals(0, count($diff));


        $subordinates = $userService->getSubordinates(3);
        $this->assertEquals(2, $subordinates->count());

        $expected = ['Emily Employee', 'Steve Trainer'];
        $diff = array_diff($expected, $subordinates->pluck('name')->toArray());

        // assert if there's no diff between our expected and our actual result
        $this->assertEquals(0, count($diff));

    }

    public function testGetNoSubordinates()
    {
        $userInput = json_decode('[{
                "Id": 1,
                "Name": "Adam Admin",
                "Role": 1
            },
            {
                "Id": 2,
                "Name": "Emily Employee",
                "Role": 4
            },
            {
                "Id": 3,
                "Name": "Sam Supervisor",
                "Role": 3
            },
            {
                "Id": 4,
                "Name": "Mary Manager",
                "Role": 2
            },
            {
                "Id":5,
                "Name": "Steve Trainer",
                "Role": 5
            }
         ]', true);
        $roleInput = json_decode('[{
                "Id": 1,
                "Name": "System Administrator",
                "Parent": 0
            },
            {
                "Id": 2,
                "Name": "Location Manager",
                "Parent": 1
            },
            {
                "Id": 3,
                "Name": "Supervisor",
                "Parent": 2
            },
            {
                "Id": 4,
                "Name": "Employee",
                "Parent": 3
            },
            {
                "Id": 5,
                "Name": "Trainer",
                "Parent": 3
            }
        ]', true);

        $persistence = new InMemoryPersistence;
        $userService = new UserService(
            new UserRepository($persistence),
            new RoleRepository($persistence)
        );
        $roleService = new RoleService(
            new RoleRepository($persistence)
        );

        $roleService->store($roleInput);
        $userService->store($userInput);

        $subordinates = $userService->getSubordinates(2);
        $this->assertEquals(0, $subordinates->count());
    }

    public function testPrimaryKeyAlreadyExistWhenStoring()
    {
        $persistence = new InMemoryPersistence;
        $service = new UserService(
            new UserRepository($persistence),
            new RoleRepository($persistence)
        );

        $userInput = json_decode('[{
                "Id": 1,
                "Name": "Adam Admin",
                "Role": 1
            },
            {
                "Id": 1,
                "Name": "Emily Employee",
                "Role": 4
            }
         ]', true);

        try {
            $service->store($userInput);
        } catch(\Exception $e) {

        }

        $this->assertTrue($e instanceof StorageException);
        $this->assertEquals('Primary key already exist', $e->getMessage());
    }
}