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
		$this->login_panel = new LoginPanel();
		$this->twig();
		if(isset($_SESSION['id'])) {
			$this->args['sid'] = $_SESSION['id'];
			$this->args['srank'] = $this->pdo->getUserRank($this->args['sid']);
			$this->args['sname'] = $this->pdo->getUserName($this->args['sid']);
		} else
			$this->args['srank'] = 100;
	}
	
	public function removeComment($vars) {
		if (isset($this->args['only']) && isset($_GET['comid']) && isset($_SESSION['id'])) {
			if($this->pdo->removeComment($_GET['comid'])) {
				header("refresh: 2;url=?action=show&only=".$this->args['only']);
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
						"message" => "Post usunięto pomyślnie!",
						"login_panel" => $this->login_panel->getData($this->args['sname'])
				));
			} else {
				header("refresh: 2;url=?action=show&only=".$this->args['only']);
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
						"message" => "Wystąpił błąd!",
						"login_panel" => $this->login_panel->getData($this->args['sname'])
				));
			}
		} else {
			header("refresh: 2;url=?action=show&only=".$this->args['only']);
			echo $this->twig->render('Index.html.twig', array(
					"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
					"message" => "Wystąpił błąd!",
					"login_panel" => $this->login_panel->getData($this->args['sname'])
			));
		}
	}
	
	public function addComment($vars) {
		if (isset($_POST['text']) && isset($this->args['only']) && isset($_SESSION['id'])) {
			if($this->pdo->addComment(new \Model\Comment(null, $_POST['text'], $_SESSION['id'], null, $this->args['only'], null))) {
				header("Location: ?action=show&only=".$this->args['only']);
			} else {
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left"),
						"message" => "Wystąpił błąd!",
						"login_panel" => $this->login_panel->getData($this->args['sname'])
				));
			}
		} else {
			$form = new \Model\Form("?action=addpost", "POST", "center", "width: 100%");
			$form->addInput("input","text", "title", "Tytuł");
			$form->addInput("textarea","text", "text", "Tekst");
			echo $this->twig->render('Index.html.twig', array(
					"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
					"form" => $form,
					"login_panel" => $this->login_panel->getData($this->args['sname'])
			));
		}
	}
	
	public function removePost($vars) {
		if (isset($this->args['id']) && isset($_SESSION['id'])) {
			if($this->pdo->removePost($this->args['id'])) {
				header("refresh: 2;url=?");
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
						"message" => "Post usunięto pomyślnie!",
						"login_panel" => $this->login_panel->getData($this->args['sname'])
				));
			} else {
				header("refresh: 2;url=?");
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
						"message" => "Wystąpił błąd!",
						"login_panel" => $this->login_panel->getData($this->args['sname'])
				));
			}
		} else {
			header("refresh: 2;url=?");
			echo $this->twig->render('Index.html.twig', array(
					"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
					"message" => "Wystąpił błąd!",
					"login_panel" => $this->login_panel->getData($this->args['sname'])
			));
		}
	}
	
	public function addPost($vars) {
		if (isset($_POST['title']) && isset($_POST['text']) && isset($_SESSION['id'])) {
			if($this->pdo->addPost(new \Model\Post(null, $_POST['title'], $_POST['text'], null, $_SESSION['id']))) {
				header("Location: ?");
			} else {
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left"),
						"message" => "Wystąpił błąd!",
						"login_panel" => $this->login_panel->getData($this->args['sname'])
				));
			}
		} else {
			$form = new \Model\Form("?action=addpost", "POST", "center", "width: 100%");
			$form->addInput("input","text", "title", "Tytuł");
			$form->addInput("textarea","text", "text", "Tekst");
			echo $this->twig->render('Index.html.twig', array(
					"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
					"form" => $form,
					"login_panel" => $this->login_panel->getData($this->args['sname'])
			));
		}
	}
	
	public function editPost($vars) {
		if (isset($_POST['title']) && isset($_POST['text']) && isset($this->args['only']) && isset($_SESSION['id'])) {
			if($this->pdo->editPost(new \Model\Post(null, $_POST['title'], $_POST['text'], null, $_SESSION['id']))) {
				header("Location: ?action=show&only=".$this->args['only']);
			} else {
				header("refresh:2;url=?action=editpost&only=".$this->args['only']);
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left"),
						"message" => "Wystąpił błąd!",
						"login_panel" => $this->login_panel->getData($this->args['sname'])
				));
			}
		} else {
			$value = $this->pdo->getPost($this->args['only']);
			$form = new \Model\Form("?action=editpost&only=".$this->args['only'], "POST", "center", "width: 100%");
			$form->addInput("input","text", "title", "Tytuł", $value->getTitle());
			$form->addInput("textarea","text", "text", "Tekst", $value->getText());
			echo $this->twig->render('Index.html.twig', array(
					"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
					"form" => $form,
					"login_panel" => $this->login_panel->getData($this->args['sname'])
			));
		}
	}
	
	public function showPosts($vars) {
		echo $this->twig->render('Index.html.twig', array(
			"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
			"main_page" => $this->pdo->getPosts($vars),
			"rank" => $this->args['srank'],
			"login_panel" => $this->login_panel->getData($this->args['sname'])
		));
	}
	
	public function showPost($vars) {
		echo $this->twig->render('Index.html.twig', array(
				"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
				"post" => $this->pdo->getPost($vars['id']),
				"rank" => $this->args['srank'],
				"login_panel" => $this->login_panel->getData($this->args['sname'])
		));
	}
	
	public function login($vars) {
		if (isset($_POST['nick']) && isset($_POST['password']) && !isset($_SESSION['id'])) {
			$login = $this->pdo->loginUser($_POST['nick'], $_POST['password']);
			if($login==0) {
				header("Location: ?");
			} else if($login==1) {
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
						"message" => "Złe hasło!",
						"login_panel" => $this->login_panel->getData($this->args['sname'])
				));
			} else if($login==2) {
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
						"message" => "Zły login!",
						"login_panel" => $this->login_panel->getData($this->args['sname'])
				));
			}
		} else if(isset($_SESSION['id'])) {
			echo $this->twig->render('Index.html.twig', array(
					"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
					"message" => "Jesteś już zalogowany!",
					"login_panel" => $this->login_panel->getData($this->args['sname'])
			));
		} else {
			$form = new \Model\Form("?action=login", "POST");
			$form->addInput("input","text", "nick", "Nick (>5 znaków, bez liczb):");
			$form->addInput("input","password", "password", "Hasło (>5 znaków, bez liczb):");
			echo $this->twig->render('Index.html.twig', array(
					"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
					"form" => $form,
					"login_panel" => $this->login_panel->getData($this->args['sname'])
			));
		}
	}
	
	public function register($vars) {
		if (isset($_POST['nick']) && isset($_POST['password']) && isset($_POST['password2']) 
				&& isset($_POST['email']) && !isset($_SESSION['id'])) {	
			if($_POST['password']!=$_POST['password2']) {
				header("refresh:2;url=?action=register");
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
						"message" => "Hasła się różnią!",
						"login_panel" => $this->login_panel->getData($this->args['sname'])
				));
				return;
			}
			if($this->pdo->registerUser($_POST['nick'], $_POST['password'],$_POST['email'])) {
				header("refresh:2;url=?");
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
						"message" => "Rejestracja pomyślna!",
						"login_panel" => $this->login_panel->getData($this->args['sname'])
				));
			} else  {
				header("refresh:2;url=?action=register");
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
						"message" => "Taki nick już istnieje!",
						"login_panel" => $this->login_panel->getData($this->args['sname'])
				));
			}
		} else if(isset($_SESSION['id'])) {
			echo $this->twig->render('Index.html.twig', array(
					"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
					"message" => "Jesteś już zalogowany!",
					"login_panel" => $this->login_panel->getData($this->args['sname'])
			));
		} else {
			$form = new \Model\Form("?action=register", "POST");
			$form->addInput("input","text", "nick", "Nick (>5 znaków, bez liczb):");
			$form->addInput("input","text", "email", "Email (zmyślony):");
			$form->addInput("input","password", "password", "Hasło (>5 znaków, bez liczb):");
			$form->addInput("input","password", "password2", "Potwierdź Hasło (>5 znaków, bez liczb):");
			echo $this->twig->render('Index.html.twig', array(
					"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
					"form" => $form,
					"login_panel" => $this->login_panel->getData($this->args['sname'])
			));
		}
	}
}