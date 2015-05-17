<?php
namespace Controller;

use Controller\Base;
use Model\Test as Test;
use Model\LoginPanel as LoginPanel;
use Model\User as User;
use Model\Menu as Menu;

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
		
		$menu = new Menu("Menu główne");
		$menu->addItem("strona główna","?");
		$menu_left = array();
		$menu_left[] = $menu->renderMenu();
		
		echo $this->twig->render('Index.html.twig', array(
			"menu_left" => $menu_left,
			$login_panel->getName() => $login_panel->getData($user->getUserName($_SESSION['id']))
		));
	}
}