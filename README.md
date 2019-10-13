### Introduction

* This is an implementation of simple User Hierarchy.
* No third party libraries are being used except for PHPUnit

### Installation
* Run `composer install` in the app's root directory

### What you need to see here?
1. main.php
    + Runs application that satisfies the requirement
        + Modify the data as needed inside the file
    + Run in your terminal with php installed `php src/main.php <id-of-user>`
        + Example: `php src/main.php 1`
        + The above will display the given user's subordinates including subordinate's subordinates
2. src/
    + Contracts - Interfaces, helps to deal with abstraction instead of concretion
        + Persistence - contains interfaces for storage classes. 
            + In this application, a simple storage interface is supported with basic inserting, retrieving single record, and getting all records.
        + Repositories - contains interfaces for repository classes that deals with database interactions
        + Services - contains interfaces for service classes that handles business logic
    + Entities - An object representation of an entity or a record in db (e.g user, role).
    + Exceptions - Custom exceptions thrown by the application
    + Factories - Contains logic for creating objects
    + Persistence - Contains classes for storage/db implementation. 
        + In this application, a simple storage is used - an InMemory storage
        + Any storage implementation will go here that implements persistence interface and can be injected into the Repositories 
    + Repositories
        + Contains classes that provides abstraction of data. Handles database interactions such as storing/fetching with clear interfaces.
    + Services
        + All business logic goes here.
    + Utilities
        + Helper classes/methods
3. tests/
    + Run test via : `vendor/bin/phpunit`
