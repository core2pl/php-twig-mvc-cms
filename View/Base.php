<?php
namespace View;

use Model\Test;

abstract class Base {
	
	abstract public function add_model(Model\Test $model);
	
	abstract function render();
}