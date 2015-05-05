<?php
include 'Model/Test.php';
include 'Controller/Index.php';

use Controler\IndexController;

$controller = new IndexController();
echo "render";
$controller->render();