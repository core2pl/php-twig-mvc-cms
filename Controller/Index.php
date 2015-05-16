<?php
namespace Controller;

use Controller\Base;
use Model\Test as Test;
use Model\LoginPanel as LoginPanel;
use Model\User as User;

class Index extends Base {
	
	public function __construct() {
		$this->model = array();
	}
	
	public function main() {
		$this->twig();
		$this->render();
	}
	
	public function render() {
		$test_model = new Test("text");
		$test_model->Read();
		
		$login_panel = new LoginPanel("login_panel");
		
		$user = new User("user");
		
		echo $this->twig->render('Index.html.twig', array(
			$test_model->getName() => $test_model->getData(),
			$login_panel->getName() => $login_panel->getData($user->getUserName($_SESSION['id']))
		));
	}
}