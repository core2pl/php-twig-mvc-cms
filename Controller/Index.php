<?php
namespace Controller;

use Controller\Base;
use Model\Test as Test;
use Model\LoginPanel as LoginPanel;
use Model\User as User;
use Model\Menu as Menu;
use Model\PDOModel as PDOModel;

class Index extends Base {
	
	private $args;
	private $pdo;
	
	public function __construct() {
		$this->model = array();
		$this->args = array();
		$this->pdo = new PDOModel("pdo");
	}
	
	public function main() {
		$this->twig();
		$this->getArgs();
		switch ($this->args['action']) {
			case "show":
				$this->showPosts();	
			break;
			case "remove":
				$this->removePost();
			break;
		}
	}
	
	private function removePost() {
		
	}
	
	private function showPosts() {
		$test_model = new Test("text");
		$test_model->Read();
		
		$login_panel = new LoginPanel("login_panel");
		
		$menu = new Menu("Menu główne");
		$menu->addItem("strona główna","?");
		$menu_left = array();
		$menu_left[] = $menu->renderMenu();
		
		$posts = $this->getPosts();
		
		echo $this->twig->render('Index.html.twig', array(
			"menus_left" => $menu_left,
			"main_page" => $posts,
			$login_panel->getName() => $login_panel->getData()
		));
	}
	
	private function getArgs() {
		if(isset($_GET['action']))
			$this->args['action'] = $_GET['action'];
		else 
			$this->args['action'] = "show";
		if(isset($_GET['order']))
			$this->args['order'] = $_GET['order'];
		else
			$this->args['order'] = "date";
	}
	
	private function getPosts() {
		$posts = $this->pdo->getPosts($this->args['order']);
		$user = new User("user");
		foreach ($posts as $postid => $post) {
			$posts[$postid]['author_name'] = $user->getUserName($post['author']);
		}
		return $posts;
	}
}