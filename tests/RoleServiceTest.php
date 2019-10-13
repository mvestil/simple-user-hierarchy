<?php

namespace Deputy\Tests;

use Deputy\Exceptions\ValidationException;
use Deputy\Persistence\InMemoryPersistence;
use Deputy\Repositories\RoleRepository;
use Deputy\Repositories\UserRepository;
use Deputy\Services\RoleService;
use Deputy\Services\UserService;
use Deputy\Utilities\Collection;
use PHPUnit\Framework\TestCase;

class RoleServiceTest extends TestCase
{
    public function testCanStoreUsers()
    {
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
        $service = new RoleService(
            new RoleRepository($persistence)
        );

        $service->store($roleInput);

        $collection = $service->all();
        $this->assertTrue($collection instanceof Collection);
        $this->assertEquals(5, $collection->count());
        $this->assertEquals(
            array_column($roleInput, 'Name'),
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

        $roleInput = null;

        try {
            $service->store($roleInput);
        } catch(\Exception $e) {

        }

        $this->assertTrue($e instanceof ValidationException);
        $this->assertEquals('Missing parameters.', $e->getMessage());

        $roleInput = json_decode('[{
                "Id": 1,
                "Parent": 0
            },
            {
                "Id": 2,
                "Parent": 1
            },
            {
                "Id": 3,
                "Parent": 2
            },
            {
                "Id": 4,
                "Parent": 3
            },
            {
                "Id": 5,
                "Parent": 3
            }
        ]', true);

        try {
            $service->store($roleInput);
        } catch(\Exception $e) {

        }

        $this->assertTrue($e instanceof ValidationException);
        $this->assertEquals('Parameter Name is required field.', $e->getMessage());

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

        try {
            $service->store($roleInput);
        } catch(\Exception $e) {

        }

        $this->assertTrue($e instanceof ValidationException);
        $this->assertEquals('Parameter Role is required field.', $e->getMessage());
    }

    public function testRoleCannotBeAParentOfItself()
    {
        $roleInput = json_decode('[{
                "Id": 1,
                "Name": "System Administrator",
                "Parent": 1
            }
        ]', true);

        $persistence = new InMemoryPersistence;
        $service = new RoleService(
            new RoleRepository($persistence)
        );

        try {
            $service->store($roleInput);
        } catch (\Exception $e) {

        }

        $this->assertTrue($e instanceof ValidationException);
        $this->assertEquals('A role cannot be a parent of itself.', $e->getMessage());
    }

    public function testInfiniteParentChildRelationship()
    {
        $roleInput = json_decode('[{
                "Id": 1,
                "Name": "System Administrator",
                "Parent": 2
            },
            {
                "Id": 2,
                "Name": "Location Manager",
                "Parent": 1
            }
        ]', true);

        $persistence = new InMemoryPersistence;
        $service = new RoleService(
            new RoleRepository($persistence)
        );

        try {
            $service->store($roleInput);
        } catch (\Exception $e) {

        }

        $this->assertTrue($e instanceof ValidationException);
        $this->assertEquals('Infinite parent-child relationship detected.', $e->getMessage());
    }
}