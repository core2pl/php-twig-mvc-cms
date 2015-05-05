<?php
namespace View;

use Model\TestModel;

abstract class BaseView {
	
	abstract public function add_model(TestModel $model);
	
	abstract function render();
}