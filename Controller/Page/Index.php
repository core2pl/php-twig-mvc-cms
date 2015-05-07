<?php
require_once 'Twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();
include 'Model/Test.php';
include 'Controller/Index.php';

use Controler\Index;

$loader = new Twig_Loader_Filesystem('Template');
$twig = new Twig_Environment($loader);

$controller = new Index();
$controller->add_twig($twig);
$controller->render();