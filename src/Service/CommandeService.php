<?php

namespace App\Service;

use App\DTO\CommandeDTO;
use App\Entity\Commande;
use App\Model\CommandeRepository;

class CommandeService
{

    public function __construct(
        public CommandeRepository $commande_repo,
    ) {}



    public function reduction(CommandeDTO $commande_dto): float
    {
        if ($commande_dto->reduction_appliquee) {
            return $commande_dto->prix_final * 0.90;
        }
        return (float) $commande_dto->prix_final;
    }

    public function callRepo(CommandeDTO $commande_dto)
    {
        $prixFinal = $this->reduction($commande_dto);
        $commande = new Commande(
            null,
            $prixFinal,
            $commande_dto->reduction_appliquee 
        );
        $this->commande_repo->saveCommande($commande);

    }
}