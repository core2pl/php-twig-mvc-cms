<?php
include 'model/test.php';
include 'controller/index.php';

use controler\Index\IndexController;

$controller = new IndexController();

$controller->render();