<?php

namespace App\Http;

use App\DTO\CommandeDTO;
use App\Service\CommandeService;

class CommandeController{

    public function __construct(
        private CommandeService $commandeService
    ){}

    public function showFormulaire(){
        if ($_SERVER['REQUEST_METHOD'] =='POST') {
            $prix_final = $_POST['prix_final'] ?? '';
            $reduction_appliquee = (bool)$_POST['reduction_appliquee'] ?? '';
            // var_dump($reduction_appliquee);die;
            $cmd = new CommandeDTO(
                $prix_final,
                $reduction_appliquee
                );
                // var_dump($cmd);die;

            $this->commandeService->callRepo($cmd);

            header("Location:/");
            }
            require_once dirname(__DIR__)."/View/Formulaire.html.php";
            }
}