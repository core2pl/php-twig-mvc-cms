<?php
namespace Controler;

require_once 'Base.php';
require_once 'View/Index.php';

use Controler\Base;
use Model\Test;
use View\Index;

class Index extends Base {

	private $views;
	private $twig;
	
	public function __construct() {
		$this->views = array();
	}
	
	public function render() {
		$test_model = new Test();
		$test_model->read();
		
		$view = new Model\Index();
		$view->add_model($test_model);
		$view->add_twig($this->twig);
		$this->views[] = $view;
		foreach ($this->views as $view) {
			$view->render();
		}
	}
	
	public function add_twig($twig) {
		$this->twig = $twig;
	}
}