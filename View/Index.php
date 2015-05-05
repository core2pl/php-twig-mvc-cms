<?php
namespace View;

require_once 'View/Base.php';

use View\BaseView;
use Model\TestModel;

class IndexView extends BaseView {

	private $models;
	
	public function add_model(TestModel $model) {
		$this->models[] = $model;
	}
	
	function render() {
		require_once 'Twig/Autoloader.php';
		Twig_Autoloader::register();
		$loader = new Twig_Loader_Filesystem('template');
		$twig = new Twig_Environment($loader);
		echo $twig->render('Index.html.twig', array(
				'text' => $models[0]->render()
		));
	}
}