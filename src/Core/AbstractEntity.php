<?php

namespace App\Core;
use DateTime;
use BadMethodCallException;


abstract class AbstractEntity
{
    public function __construct(
        protected ?int $id = null,
        protected DateTime $date_creation = new DateTime()
    ) {
    }

    public function __call(string $name, array $arguments): mixed
    {
        if (str_starts_with($name, 'get')) {
            $property = lcfirst(substr($name, 3));

            return $this->$property;
        }

        if (str_starts_with($name, 'set')) {
            $property = lcfirst(substr($name, 3));

            $this->$property = $arguments[0];

            return $this;
        }

        throw new BadMethodCallException("La méthode $name n'existe pas.");
    }
}