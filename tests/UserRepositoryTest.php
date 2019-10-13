<?php

namespace Deputy\Tests;

use Deputy\Entities\User;
use Deputy\Exceptions\RecordNotFoundException;
use Deputy\Persistence\InMemoryPersistence;
use Deputy\Repositories\UserRepository;
use Deputy\Utilities\Collection;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    public function testInsertAndFind()
    {
        $repository = new UserRepository(new InMemoryPersistence());

        $user = User::draft([
            'id' => 100,
            'name' => 'Test Name',
            'roleId' => 101
        ]);

        $repository->insert($user);
        $this->assertEquals($user, $repository->find(100));;
    }

    public function testCannotFind()
    {
        $repository = new UserRepository(new InMemoryPersistence());

        $user = User::draft([
            'id' => 100,
            'name' => 'Test Name',
            'roleId' => 101
        ]);

        $repository->insert($user);

        $this->assertNull($repository->find(9999));
    }

    public function testAllInRoles()
    {
        $repository = new UserRepository(new InMemoryPersistence());

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

        foreach ($userInput as $input) {
            $repository->insert(User::draft([
                'id' => $input['Id'],
                'name' => $input['Name'],
                'roleId' => $input['Role'],
            ]));
        }

        $usersInRoles = $repository->allInRoles([1]);

        $this->assertTrue($usersInRoles instanceof Collection);
        $this->assertEquals(1, $usersInRoles->count());
        $this->assertEquals(1, $usersInRoles->first()->id);
        $this->assertEquals('Adam Admin', $usersInRoles->first()->name);
        $this->assertEquals(1, $usersInRoles->first()->roleId);
    }
}
