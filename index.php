<?php

require "vendor/autoload.php";

use App\Http\CommandeController;
use App\Core\Container;
use App\Model\CommandeRepository;
use App\Service\CommandeService;


$container = new Container();


$container->get(CommandeRepository::class);
$container->get(CommandeService::class);
$controller = $container->get(CommandeController::class);
$controller->showFormulaire();