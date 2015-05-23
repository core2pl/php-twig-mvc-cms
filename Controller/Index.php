<?php
namespace Controller;

use Controller\Base;
use Model\Test as Test;
use Model\LoginPanel as LoginPanel;
use Model\User as User;
use Model\Menu as Menu;

class Index extends Base {
	
	private $args;
	private $login_panel;
	
	public function __construct() {
		$this->args = array();
		$this->login_panel = new LoginPanel("login_panel");
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
			case "addpost":
				$this->addPost();
			break;
		}
	}
	
	private function removePost() {
		if (isset($this->args['id'])) {
			if($this->pdo->removePost($this->args['id'])) {
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left"),
						"message" => "Post usunięto pomyślnie!",
						$this->login_panel->getName() => $this->login_panel->getData()
				));
			} else {
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left"),
						"message" => "Wystąpił błąd!",
						$this->login_panel->getName() => $this->login_panel->getData()
				));
			}
		}
	}
	
	private function addPost() {
		if (isset($_POST['title']) && isset($_POST['text']) && isset($_SESSION['id'])) {
			if($this->pdo->addPost($_POST['title'], $_POST['text'], $_SESSION['id'])) {
				$this->showPosts();
			} else {
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left"),
						"message" => "Wystąpił błąd!",
						$this->login_panel->getName() => $this->login_panel->getData()
				));
			}
		} else {
			$form = new \Model\Form("?action=addpost", "POST");
			$form->addInput("text", "title", "Tytuł");
			$form->addInput("text", "text", "Tekst");
			$form->addInput("hidden", "author", "", $_SESSION['id']);
			echo $this->twig->render('Index.html.twig', array(
					"menus_left" => $this->menu->makeMenu("left"),
					"form" => $form,
					$this->login_panel->getName() => $this->login_panel->getData()
			));
		}
	}
	
	private function showPosts() {
		$test_model = new Test("text");
		$test_model->Read();
		
		
		
		echo $this->twig->render('Index.html.twig', array(
			"menus_left" => $this->menu->makeMenu("left"),
			"main_page" => $this->pdo->getPosts(),
			$this->login_panel->getName() => $this->login_panel->getData()
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
		if(isset($_GET['id'])) 
			$this->args['id'] = $_GET['id'];
		if(isset($_GET['only'])) 
			$this->args['only'] = $_GET['only'];
	}
}