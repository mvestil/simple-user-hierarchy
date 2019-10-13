<?php

namespace Deputy\Tests;

use Deputy\Entities\Role;
use Deputy\Exceptions\RecordNotFoundException;
use Deputy\Persistence\InMemoryPersistence;
use Deputy\Repositories\RoleRepository;
use PHPUnit\Framework\TestCase;

class RoleRepositoryTest extends TestCase
{
    public function testInsertAndFind()
    {
        $repository = new RoleRepository(new InMemoryPersistence());

        $role = Role::draft([
            'id' => 1,
            'name' => 'Sample Role Name',
            'parentId' => 0
        ]);

        $repository->insert($role);
        $this->assertEquals($role, $repository->find(1));
    }

    public function testCannotFind()
    {
        $repository = new RoleRepository(new InMemoryPersistence());

        $role = Role::draft([
            'id' => 1,
            'name' => 'Test Name',
            'parentId' => 0
        ]);

        $repository->insert($role);

        $this->assertNull($repository->find(9999));
    }

    public function testGetChildrenRolesOfAParentRole()
    {
        $repository = new RoleRepository(new InMemoryPersistence());

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

        foreach ($roleInput as $input) {
            $repository->insert(Role::draft([
                'id' => $input['Id'],
                'name' => $input['Name'],
                'parentId' => $input['Parent'],
            ]));
        }

        $this->assertEquals(5, $repository->all()->count());

        $this->assertEquals(4, $repository->childrenOf(1)->count());
//        $this->assertEquals(3, $repository->childrenOf(2)->count());
//        $this->assertEquals(2, $repository->childrenOf(3)->count());
//        $this->assertEquals(0, $repository->childrenOf(4)->count());
//        $this->assertEquals(0, $repository->childrenOf(5)->count());


        //print_r();
    }
}
