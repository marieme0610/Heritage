<?php

namespace App\Core;

class Container 
{
    private array $instances = [];


    public function get(string $className) 
    {
        if (isset($this->instances[$className])) {
            return $this->instances[$className];
        }

        $reflector = new \ReflectionClass($className);

        if (!$reflector->isInstantiable()) {
            throw new \Exception("La classe {$className} n'est pas instanciable.");
        }

        $constructor = $reflector->getConstructor();

        if ($constructor === null) {
            $object = new $className();
            $this->instances[$className] = $object;
            return $object;
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $param) {
            $type = $param->getType();

            if ($type === null) {
                throw new \Exception("Impossible de résoudre le paramètre '{$param->getName()}' dans {$className} : Aucun type spécifié.");
            }

            $dependencyClassName = $type->getName();

            $dependencies[] = $this->get($dependencyClassName);
        }

        $object = $reflector->newInstanceArgs($dependencies);

        $this->instances[$className] = $object;
        return $object;
    }
}