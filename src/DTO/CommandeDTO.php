<?php

namespace App\DTO;

readonly class CommandeDTO
{
    public function __construct(
        public float $prix_final,
        public bool $reduction_appliquee
    ) {}
}