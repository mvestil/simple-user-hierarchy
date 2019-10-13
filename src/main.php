<?php

require 'vendor/autoload.php';


use Deputy\Factories\Factory;
use Deputy\Persistence\InMemoryPersistence;

try {
    $persistence = new InMemoryPersistence();

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

    $userInput = json_decode(' [{
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
                "Id": 5,
                "Name": "Steve Trainer",
                "Role": 5
            }
         ]', true);

    $roleController = initRoleController($persistence);
    $roleController->store($roleInput);

    $userController = initUserController($persistence);
    $userController->store($userInput);
    $subordinates = $userController->subordinates($argv[1]);

    echo $subordinates->toJson();
} catch (\Exception $e) {
    echo $e->getMessage();
}



function initUserController($persistence)
{
    // Init User
    $userService = Factory::makeService('User', [
        Factory::makeRepository('User', [$persistence]),
        Factory::makeRepository('Role', [$persistence])
    ]);

    return Factory::makeController('User', [$userService]);
}

function initRoleController($persistence)
{
    $roleService = Factory::makeService('Role', [
        Factory::makeRepository('Role', [$persistence])
    ]);

    return Factory::makeController('Role', [$roleService]);
}