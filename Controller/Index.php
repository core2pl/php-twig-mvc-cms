<?php
namespace Controler;

require_once 'Base.php';
require_once 'View/Index.php';

use Controler\BaseController;
use Model\TestModel;
use View\IndexView;

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