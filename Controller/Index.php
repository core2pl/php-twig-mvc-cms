<?php
namespace Controler;

require_once 'Base.php';

use Controler\Base;
use Model\Test as M_Test;
use Model\Model;

class Index extends Base {

	private $models;
	private $twig;
	
	public function __construct() {
		$this->model = array();
	}
	
	public function main() {
		$this->render();
	}
	
	public function render() {
		$test_model = new M_Test("text");
		$test_model->read();
		
		$this->models[] = $test_model;
		
		$render = array();
		foreach ($this->models as $model) {
			echo $this->twig->render('Index.html.twig', array(
					$model->getName() => $model->getData()
			));
		}
	}
	
	public function add_twig($twig) {
		$this->twig = $twig;
	}
}