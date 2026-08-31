<?php

require "vendor/autoload.php";

use App\DTO\CommandeDTO;
use App\Model\CommandeRepository;

$commande = new CommandeDTO(
    25000,
    false
);

$cmdSave = new CommandeRepository();

$cmdSave->saveCommande($commande);

