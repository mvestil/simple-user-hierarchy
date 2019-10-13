<?php

namespace Deputy\Factories;

/**
 * Class Factory
 * @package Deputy\Factories
 */
class Factory
{
    /**
     * Initialize the class
     *
     * @param string $class
     * @param array|null $dependencies
     * @return mixed
     */
    protected static function initFullClassName(string $class, array $dependencies = null)
    {
        if ($dependencies) {
            return new $class(...$dependencies);
        }

        return new $class();
    }

    /**
     * Initialize a controller
     *
     * @param string $class
     * @param array|null $dependencies
     * @return mixed
     */
    public static function makeController(string $class, array $dependencies = null)
    {
        return static::initFullClassName(
            'Deputy\\Controllers\\' . $class . 'Controller',
            $dependencies
        );
    }

    /**
     * Initialize a repository
     *
     * @param string $class
     * @param array|null $dependencies
     * @return mixed
     */
    public static function makeRepository(string $class, array $dependencies = null)
    {
        return static::initFullClassName(
            'Deputy\\Repositories\\' . $class . 'Repository',
            $dependencies
        );
    }

    /**
     * Initialize a service
     *
     * @param string $class
     * @param array|null $dependencies
     * @return mixed
     */
    public static function makeService(string $class, array $dependencies = null)
    {
        return static::initFullClassName(
            'Deputy\\Services\\' . $class . 'Service',
            $dependencies
        );
    }
}