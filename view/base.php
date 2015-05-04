<?php
namespace view;

use model\Test\TestModel;

abstract class BaseView {
	
	abstract function add_model(model\Test\TestModel $model);
	
	abstract function render();
}