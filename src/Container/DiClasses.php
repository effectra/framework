<?php

namespace Effectra\Core\Container;

use Effectra\Core\Application;
use RuntimeException;

/**
 * Class DiClasses
 *
 * A Dependency Injection container that resolves and loads classes with their dependencies.
 */
class DiClasses
{
     /**
     * Load a class with its dependencies resolved and instantiated.
     *
     * @param string $class The fully qualified name of the class to load.
     * @return object An instance of the loaded class with resolved dependencies.
     * @throws RuntimeException If the class is not defined or there is an error resolving dependencies.
     */
    public static function load($class):object
    {
        if (!class_exists($class)) {
            throw new RuntimeException("Class $class not defined");
        }
        $reflection = new \ReflectionClass($class);
        $dependencies = [];
        $constructor = $reflection->getConstructor();
        if ($constructor) {
            // Get the parameters of the constructor
            $parameters = $constructor->getParameters();
            if (count($parameters) !== 0) {

                foreach ($parameters as $parameter) {
                    $parameterType = $parameter->getType();

                    // Check if the parameter has a type
                    if ($parameterType !== null) {
                        // Get the name of the dependency class
                        $dependencyClass = $parameterType->getName();

                        // Resolve the dependency instance from the container
                        $dependencyInstance = Application::container()->get($dependencyClass);

                        // Add the dependency instance to the array
                        $dependencies[] = $dependencyInstance;
                    }
                }
                return  new $class(...$dependencies);
            }
        } 
        return new $class();
    }
}
