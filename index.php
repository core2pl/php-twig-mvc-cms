<?php
session_start();
require 'SPLAutoLoad.php';
require 'mysqlpass.php';
require 'Install.php';


$autoload = new SPLAutoLoad();
$autoload->main();
use Controller\Page\Index;
$install = new Install();
$install->install();
$index = new Index();
