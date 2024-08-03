<?php
namespace Core;
class Factory {
    public static function instantiateClass($className) {
        // var_dump("ddd",$className);
        try {
            $reflectionClass = new \ReflectionClass($className);
            return $reflectionClass->newInstance();
        } catch (\ReflectionException $e) {
            throw new \Exception("Class $className not found or not instantiable");
        }
    }
}