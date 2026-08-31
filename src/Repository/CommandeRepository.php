<?php

namespace App\Model;
use App\DTO\CommandeDTO;
use App\Core\Database;

class CommandeRepository
{
    public function saveCommande(CommandeDTO $commande): int
    {
        $params = [
            'prix_final'=> $commande->prix_final,
            'reduction_appliquee'=> $commande->reduction_appliquee
        ];
        $sql = "INSERT INTO commandes(
                prix_final,reduction_appliquee
                )
                VALUES(:prix_final,:reduction_appliquee);";
    
    $query = Database::executeUpdate($sql,$params);
    return $query;
    }


}