<?php
namespace view\Index;

require_once 'view/base.php';

use view\BaseView;
use model\Test\TestModel;

class IndexView extends BaseView {

	private $models;
	
	public function add_model(model\Test\TestModel $model) {
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