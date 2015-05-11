<?php
include 'mysqlpass.php';
if(isset($_GET['page'])) {
	switch ($_GET['page']) {
	}
} else {
	include 'Controller/Page/Index.php';
}