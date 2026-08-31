<?php

namespace App\Entity;
use DateTime;
use BadMethodCallException;

class Commande
{
    private DateTime $date_creation;

    public function __construct(
        private ?int $id,
        private float $prix_final,
        private bool $reduction_appliquee,
        ?DateTime $date_creation = null
    ) {
        $this->date_creation = $date_creation ?? new DateTime();
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