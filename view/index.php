<?php
namespace view\Index;

use view\BaseView;

class IndexView extends BaseView {

	private $models;
	
	public function add_model(\model $model) {
		$this->models[] = $model;
	}
	
	function render() {
		require_once 'Twig/Autoloader.php';
		Twig_Autoloader::register();
		$loader = new Twig_Loader_Filesystem('template');
		$twig = new Twig_Environment($loader);
		echo $twig->render('index.html.twig', array(
				'text' => $model->render()
		));
	}
}