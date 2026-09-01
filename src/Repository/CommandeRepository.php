<?php

namespace App\Model;

use App\Core\Database;
use App\Entity\Commande;

class CommandeRepository 
{
    public function __construct(
       private Database $db
    ){}
    public function saveCommande(Commande $commande)
    {
        $params = [
            'prix_final' => $commande->getPrix_final(),
            'reduction_appliquee' => $commande->getReduction_appliquee()
        ];
        
        $sql = "INSERT INTO commandes (prix_final, reduction_appliquee)
                VALUES (:prix_final, :reduction_appliquee)";

        $res = $this->db->executeQuery($sql, $params, true);

        return $res->id ?? null;
    }
}