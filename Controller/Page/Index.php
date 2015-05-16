<?php
namespace Controller\Page;

use Controller\Index as IndexController;

class Index {
	
	function __construct() {
		$ctrl = new IndexController();
		$ctrl->main();
	}
}