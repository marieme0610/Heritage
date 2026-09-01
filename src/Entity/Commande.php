<?php

namespace App\Entity;

use App\Core\AbstractEntity;

class Commande extends AbstractEntity
{
    public function __construct(
        ?int $id = null,
        protected ?float $prix_final = null,
        protected bool $reduction_appliquee = false
    ) {
        parent::__construct($id);
    }
}