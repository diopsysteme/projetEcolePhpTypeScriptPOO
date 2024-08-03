<?php

namespace Core;

use Symfony\Component\Yaml\Yaml;

class ServiceProvider
{
    public static function register(Container $container, $configFile)
    {
        $config = Yaml::parseFile($configFile);

        foreach ($config['services'] as $name => $serviceConfig) {
            $className = $serviceConfig['class'];
            $arguments = isset($serviceConfig['arguments']) ? $serviceConfig['arguments'] : [];

            foreach ($arguments as &$argument) {
                if (preg_match('/@([a-zA-Z0-9_]+)/', $argument, $matches)) {
                    $serviceName = $matches[1];
                    $argument = $container->get($serviceName);
                } elseif (preg_match('/%env\((.*?)\)%/', $argument, $matches)) {
                    $envVar = $matches[1];
                    $argument = str_replace("%env($envVar)%", getenv($envVar), $argument);
                }
            }

            $container->register($name, function($c) use ($className, $arguments) {
                $reflectionClass = new \ReflectionClass($className);
                return $reflectionClass->newInstanceArgs($arguments);
            });
        }

        // Register a factory for all controllers
        $container->register('controller_factory', function($c) {
            return function($controllerName) use ($c) {
                $reflectionClass = new \ReflectionClass($controllerName);
                $constructor = $reflectionClass->getConstructor();
                $dependencies = [];

                if ($constructor) {
                    foreach ($constructor->getParameters() as $parameter) {
                        $type = $parameter->getType();
                        // var_dump($parameter);
                        if ($type && !$type->isBuiltin()) {
                            $dependencyClassName = $type->getName();
                            $serviceName = strtolower((new \ReflectionClass($dependencyClassName))->getShortName());
                            // var_dump($serviceName);
                            $dependencies[] = $c->get($serviceName);
                        } else {
                            $dependencies[] = null;
                        }
                    }
                }

                return $reflectionClass->newInstanceArgs($dependencies);
            };
        });
    }
}
