<?php
require 'SPLAutoLoad.php';
require 'mysqlpass.php';

use Controller\Page\Index;

$autoload = new SPLAutoLoad();
$autoload->main();
if(isset($_GET['page'])) {
	switch ($_GET['page']) {
	}
} else {
	$index = new Index();
}