<?php
namespace View;

require_once 'View/Base.php';

use View\BaseView;
use Model\TestModel;

class IndexView extends BaseView {

	private $models;
	private $twig;
	
	public function add_model(TestModel $model) {
		$this->models[] = $model;
	}
	
	function render() {
		print_r($this->models);
		echo $this->twig->render('Index.html.twig', array(
				'text' => $this->models[0]->get()
		));
	}
	
	public function add_twig($twig) {
		$this->twig = $twig;
	}
}