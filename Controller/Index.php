<?php
namespace Controler;

require_once 'Base.php';

use Controler\Base;
use Model\Test as M_Test;
use Model\LoginPanel;
use Model\User;

class Index extends Base {

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
		
		$login_panel = new LoginPanel("login_panel");
		
		$user = new User("user");
		
		
		
		echo $this->twig->render('Index.html.twig', array(
			$model->getName() => $model->getData(),
			$login_panel->getName() => $login_panel->getData($user->getUserName($_SESSION['id']))
		));
	}
	
	public function add_twig($twig) {
		$this->twig = $twig;
	}
}