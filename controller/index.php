<?php
namespace controler\Index;

require_once 'base.php';
require_once 'view/index.php';

use controler\BaseController;
use model\Test\TestModel;
use view\Index\IndexView;

class IndexController extends BaseController{

	private $views;
	
	public function __construct() {
		$this->views = array();
		
		$test_model = new TestModel();
		$test_model->read();
		
		$view = new IndexView();
		$view->add_model($test_model);
		
	}
	
	public function render() {
		foreach ($this->views as $view) {
			$view->render();
		}
	}
}