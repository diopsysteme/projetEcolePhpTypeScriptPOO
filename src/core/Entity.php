<?php
namespace Core;
class Entity {

    public function __get($property)
    {
        $reflectionClass = new \ReflectionClass($this);
        if ($reflectionClass->hasProperty($property)) {
            $reflectionProperty = $reflectionClass->getProperty($property);
            $reflectionProperty->setAccessible(true);
            return $reflectionProperty->getValue($this);
        }
        return null; 
    }
    
    public function __set($property, $value)
    {
        $reflectionClass = new \ReflectionClass($this);
        if ($reflectionClass->hasProperty($property)) {
            $reflectionProperty = $reflectionClass->getProperty($property);
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($this, $value);
            return $this;
        }
        throw new \Exception("Property $property does not exist");
    }

    //toObject() method
    //toArray
    public function toArray()
    {
        $reflectionClass = new \ReflectionClass($this);
        $properties = $reflectionClass->getProperties();
        $array = [];
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($this);
        }
        return $array;
    }

    public function toObject(array $data)
    {
        $reflectionClass = new \ReflectionClass($this);
        foreach ($data as $property => $value) {
            if ($reflectionClass->hasProperty($property)) {
                $reflectionProperty = $reflectionClass->getProperty($property);
                $reflectionProperty->setAccessible(true);
                $reflectionProperty->setValue($this, $value);
            }
        }
        return $this;
    }

    public function serialize()
    {
        return json_encode($this->toArray());
    }

    public function unserialize($data)
    {
        $array = json_decode($data, true);
        return $this->toObject($array);
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
    
}