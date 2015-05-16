<?php
require 'SPLAutoLoad.php';
require 'mysqlpass.php';
$autoload = new SPLAutoLoad();
use Controller\Page\Index;
if(isset($_GET['page'])) {
	switch ($_GET['page']) {
	}
} else {
	$autoload->main();
	$index = new Index();
}