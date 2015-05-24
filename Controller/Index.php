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
				if(isset($this->args['only']))
					$this->showPost();
				else
					$this->showPosts();	
			break;
			case "remove":
				$this->removePost();
			break;
			case "addpost":
				$this->addPost();
			break;
			case "login":
				$this->login();
			break;
			case "logout":
				session_unset();
				session_destroy();
				header("Location: ?");
			break;
			case "register":
				$this->register();
			break;
		}
	}
	
	private function removePost() {
		if (isset($this->args['id'])) {
			if($this->pdo->removePost($this->args['id'])) {
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
						"message" => "Post usunięto pomyślnie!",
						$this->login_panel->getName() => $this->login_panel->getData($this->args['sname'])
				));
			} else {
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
						"message" => "Wystąpił błąd!",
						$this->login_panel->getName() => $this->login_panel->getData($this->args['sname'])
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
						$this->login_panel->getName() => $this->login_panel->getData($this->args['sname'])
				));
			}
		} else {
			$form = new \Model\Form("?action=addpost", "POST", "center", "width: 100%");
			$form->addInput("input","text", "title", "Tytuł");
			$form->addInput("textarea","text", "text", "Tekst");
			echo $this->twig->render('Index.html.twig', array(
					"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
					"form" => $form,
					$this->login_panel->getName() => $this->login_panel->getData($this->args['sname'])
			));
		}
	}
	
	private function showPosts() {
		echo $this->twig->render('Index.html.twig', array(
				"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
			"main_page" => $this->pdo->getPosts(),
			$this->login_panel->getName() => $this->login_panel->getData($this->args['sname'])
		));
	}
	
	private function showPost() {
		echo $this->twig->render('Index.html.twig', array(
				"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
				"main_page" => $this->pdo->getPost($this->args['only']),
				$this->login_panel->getName() => $this->login_panel->getData($this->args['sname'])
		));
	}
	
	private function login() {
		if (isset($_POST['nick']) && isset($_POST['password']) && !isset($_SESSION['id'])) {
			$login = $this->pdo->loginUser($_POST['nick'], $_POST['password']);
			if($login==0) {
				header("Location: ?");
			} else if($login==1) {
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
						"message" => "Złe hasło!",
						$this->login_panel->getName() => $this->login_panel->getData($this->args['sname'])
				));
			} else if($login==2) {
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
						"message" => "Zły login!",
						$this->login_panel->getName() => $this->login_panel->getData($this->args['sname'])
				));
			}
		} else if(isset($_SESSION['id'])) {
			echo $this->twig->render('Index.html.twig', array(
					"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
					"message" => "Jesteś już zalogowany!",
					$this->login_panel->getName() => $this->login_panel->getData($this->args['sname'])
			));
		} else {
			$form = new \Model\Form("?action=login", "POST", "center", "width: 100%");
			$form->addInput("input","text", "nick", "Nick (>5 znaków, bez liczb):");
			$form->addInput("input","password", "password", "Hasło (>5 znaków, bez liczb):");
			echo $this->twig->render('Index.html.twig', array(
					"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
					"form" => $form,
					$this->login_panel->getName() => $this->login_panel->getData($this->args['sname'])
			));
		}
	}
	
	private function register() {
		
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
		if(isset($_SESSION['id'])) {
			$this->args['sid'] = $_SESSION['id'];
			$this->args['srank'] = $this->pdo->getUserRank($this->args['sid']);
			$this->args['sname'] = $this->pdo->getUserName($this->args['sid']);
		} else
			$this->args['srank'] = 100;
		
	}
}