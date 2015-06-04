<?php
namespace Controller;

use Controller\Base;
use Service\LoginPanel as LoginPanel;
use Model\User as User;
use Model\Menu as Menu;
use Service\Table;

class Index extends Base {
	
	private $args;
	private $login_panel;
	
	public function __construct() {
		$this->login_panel = new LoginPanel();
		$this->twig();
		if(isset($_SESSION['id'])) {
			if(!$this->pdo->userExists($_SESSION['id'])) {
				$this->logout();
				return;
			}
			$this->pdo->setLastLogin($_SESSION['id']);
			$this->args['sid'] = $_SESSION['id'];
			$this->args['srank'] = $this->pdo->getUserRank($this->args['sid']);
			$this->args['sname'] = $this->pdo->getUserName($this->args['sid']);
		} else
			$this->args['srank'] = 3;
		
		
	}
	
	public function showPosts($vars) {
		if(isset($_POST['order'])) {
			header("Location: /show/".$_POST['order']);
			return;
		}
		echo $this->twig->render('Index.html.twig', array(
			"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
			"main_page" => $this->pdo->getPosts($vars['order']),
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
	
	public function modifyComment($vars) {
		switch ($vars['action']) {
			case 'add':
				if (isset($_POST['text']) && isset($vars['id']) && isset($_SESSION['id'])) {
					if($this->pdo->addComment(new \Model\Comment(null, $_POST['text'], $_SESSION['id'], null, $vars['id'], null))) {
						header("Location: /post/".$vars['id']);
					} else {
						echo $this->twig->render('Index.html.twig', array(
								"menus_left" => $this->menu->makeMenu("left"),
								"message" => "Wystąpił błąd!",
								"rank" => $this->args['srank'],
							"login_panel" => $this->login_panel->getData($this->args['sname'])
						));
					}
				} else {
					header("refresh: 2;url=/post/".$vars['id']);
					echo $this->twig->render('Index.html.twig', array(
							"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
							"message" => "Wystąpił błąd!",
							"rank" => $this->args['srank'],
							"login_panel" => $this->login_panel->getData($this->args['sname'])
					));
				}
			break;
			case 'remove':
				if (isset($vars['id']) && isset($_POST['com_id']) && isset($_SESSION['id']) && $this->args['srank'] == 1) {
					if($this->pdo->removeComment($_POST['com_id'])) {
						header("refresh: 2;url=/post/".$vars['id']);
						echo $this->twig->render('Index.html.twig', array(
								"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
								"message" => "Komentarz usunięto pomyślnie!",
								"rank" => $this->args['srank'],
							"login_panel" => $this->login_panel->getData($this->args['sname'])
						));
					} else {
						header("refresh: 2;url=/post/".$vars['id']);
						echo $this->twig->render('Index.html.twig', array(
								"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
								"message" => "Wystąpił błąd!",
								"rank" => $this->args['srank'],
							"login_panel" => $this->login_panel->getData($this->args['sname'])
						));
					}
				} else {
					header("refresh: 2;url=/post/".$this->args['only']);
					echo $this->twig->render('Index.html.twig', array(
							"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
							"message" => "Wystąpił błąd!",
							"rank" => $this->args['srank'],
							"login_panel" => $this->login_panel->getData($this->args['sname'])
					));
				}
			break;
		}
	}
	
	public function modifyPost($vars) {
		switch ($vars['action']) {
			case 'remove':
				if (isset($vars['id']) && isset($_SESSION['id']) && $this->args['srank'] == 1) {
					if($this->pdo->removePost($vars['id'])) {
						header("refresh: 2;url=/");
						echo $this->twig->render('Index.html.twig', array(
								"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
								"message" => "Post usunięto pomyślnie!",
								"rank" => $this->args['srank'],
							"login_panel" => $this->login_panel->getData($this->args['sname'])
						));
					} else {
						header("refresh: 2;url=/");
						echo $this->twig->render('Index.html.twig', array(
								"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
								"message" => "Wystąpił błąd!",
								"rank" => $this->args['srank'],
							"login_panel" => $this->login_panel->getData($this->args['sname'])
						));
					}
				} else {
					header("refresh: 2;url=/");
					echo $this->twig->render('Index.html.twig', array(
							"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
							"message" => "Wystąpił błąd!",
							"rank" => $this->args['srank'],
							"login_panel" => $this->login_panel->getData($this->args['sname'])
					));
				}
			break;
			case 'add':
				if (isset($_POST['title']) && isset($_POST['text']) && isset($_SESSION['id']) && $this->args['srank'] == 1) {
					if($this->pdo->addPost(new \Model\Post(null, $_POST['title'], $_POST['text'], null, $_SESSION['id']))) {
						header("Location: /");
					} else {
						header("refresh: 2;url=/");
						echo $this->twig->render('Index.html.twig', array(
								"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
								"message" => "Wystąpił błąd!",
								"rank" => $this->args['srank'],
							"login_panel" => $this->login_panel->getData($this->args['sname'])
						));
					}
				} else {
					$form = new \Service\Form("/post/add/0", "POST", "center", "width: 100%");
					$form->addInput("input","text", "title", "Tytuł");
					$form->addInput("textarea","text", "text", "Tekst");
					echo $this->twig->render('Index.html.twig', array(
							"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
							"form" => $form,
							"rank" => $this->args['srank'],
							"login_panel" => $this->login_panel->getData($this->args['sname'])
					));
				}
			break;
			case 'edit':
				if (isset($_POST['title']) && isset($_POST['text']) && isset($vars['id']) && isset($_SESSION['id']) && $this->args['srank'] == 1) {
					if($this->pdo->editPost(new \Model\Post($vars['id'], $_POST['title'], $_POST['text'], null, $_SESSION['id']))) {
						header("Location: /post/".$vars['id']);
					} else {
						header("refresh:2;url=/post/edit/".$vars['id']);
						echo $this->twig->render('Index.html.twig', array(
								"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
								"message" => "Wystąpił błąd!",
								"rank" => $this->args['srank'],
							"login_panel" => $this->login_panel->getData($this->args['sname'])
						));
					}
				} else if(isset($_SESSION['id']) && $this->args['srank'] == 1) {
					$value = $this->pdo->getPost($vars['id']);
					$form = new \Service\Form("/post/edit/".$vars['id'], "POST", "center", "width: 100%");
					$form->addInput("input","text", "title", "Tytuł", $value->getTitle());
					$form->addInput("textarea","text", "text", "Tekst", $value->getText());
					echo $this->twig->render('Index.html.twig', array(
							"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
							"form" => $form,
							"rank" => $this->args['srank'],
							"login_panel" => $this->login_panel->getData($this->args['sname'])
					));
				} else {
					header("Location: /");
				}
			break;
			default:
				header("Location: /");
			break;
		}
	}
	
	public function login($vars) {
		if (isset($_POST['nick']) && isset($_POST['password']) && !isset($_SESSION['id'])) {
			$login = $this->pdo->loginUser($_POST['nick'], $_POST['password']);
			if($login==0) {
				header("Location: /");
			} else if($login==1) {
				header("refresh:2;url=/login");
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
						"message" => "Złe hasło!",
						"rank" => $this->args['srank'],
						"login_panel" => $this->login_panel->getData($this->args['sname'])
				));
			} else if($login==2) {
				header("refresh:2;url=/login");
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
						"message" => "Zły login!",
						"rank" => $this->args['srank'],
						"login_panel" => $this->login_panel->getData($this->args['sname'])
				));
			}
		} else if(isset($_SESSION['id'])) {
			header("refresh:2;url=/");
			echo $this->twig->render('Index.html.twig', array(
					"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
					"message" => "Jesteś już zalogowany!",
					"rank" => $this->args['srank'],
					"login_panel" => $this->login_panel->getData($this->args['sname'])
			));
		} else {
			$form = new \Service\Form("/login", "POST");
			$form->addInput("input","text", "nick", "Nick (>5 znaków, bez liczb):");
			$form->addInput("input","password", "password", "Hasło (>5 znaków, bez liczb):");
			echo $this->twig->render('Index.html.twig', array(
					"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
					"form" => $form,
					"rank" => $this->args['srank'],
					"login_panel" => $this->login_panel->getData($this->args['sname'])
			));
		}
	}
	
	public function logout() {
		session_unset();
		session_destroy();
		header("Location: /");
	}
	
	public function register($vars) {
		if (isset($_POST['nick']) && isset($_POST['password']) && isset($_POST['password2']) 
				&& isset($_POST['email']) && !isset($_SESSION['id'])) {	
			if($_POST['password']!=$_POST['password2']) {
				header("refresh:2;url=/register");
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
						"message" => "Hasła się różnią!",
						"rank" => $this->args['srank'],
						"login_panel" => $this->login_panel->getData($this->args['sname'])
				));
				return;
			}
			if($this->pdo->registerUser($_POST['nick'], $_POST['password'],$_POST['email'])) {
				header("refresh:2;url=/");
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
						"message" => "Rejestracja pomyślna!",
						"rank" => $this->args['srank'],
						"login_panel" => $this->login_panel->getData($this->args['sname'])
				));
			} else  {
				header("refresh:2;url=/register");
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
						"message" => "Taki nick już istnieje!",
						"rank" => $this->args['srank'],
						"login_panel" => $this->login_panel->getData($this->args['sname'])
				));
			}
		} else if(isset($_SESSION['id'])) {
			header("refresh:2;url=/");
			echo $this->twig->render('Index.html.twig', array(
					"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
					"message" => "Jesteś już zalogowany!",
					"rank" => $this->args['srank'],
					"login_panel" => $this->login_panel->getData($this->args['sname'])
			));
		} else {
			$form = new \Service\Form("/register", "POST");
			$form->addInput("input","text", "nick", "Nick (>5 znaków, bez liczb):");
			$form->addInput("input","text", "email", "Email (zmyślony):");
			$form->addInput("input","password", "password", "Hasło (>5 znaków, bez liczb):");
			$form->addInput("input","password", "password2", "Potwierdź Hasło (>5 znaków, bez liczb):");
			echo $this->twig->render('Index.html.twig', array(
					"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
					"form" => $form,
					"rank" => $this->args['srank'],
					"login_panel" => $this->login_panel->getData($this->args['sname'])
			));
		}
	}
	
	public function users($vars) {
		$this->user($vars);
	} 
	
	public function user($vars) {
		switch ($vars['action']) {
			case "show":
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
						"user" => $this->pdo->getUserData($vars['id']),
						"rank" => $this->args['srank'],
						"login_panel" => $this->login_panel->getData($this->args['sname'])
				));
			break;
			case "remove":
				if(isset($_SESSION['id']) && $this->args['srank'] == 1) {
					if($this->pdo->removeUser($_POST['id'])) {
						header("refresh:2;url=/");
						echo $this->twig->render('Index.html.twig', array(
								"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
								"message" => "Usunięto użykownika pomyślnie!",
								"rank" => $this->args['srank'],
								"login_panel" => $this->login_panel->getData($this->args['sname'])
						));
					} else {
						header("refresh:2;url=/");
						echo $this->twig->render('Index.html.twig', array(
								"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
								"message" => "Wystąpił błąd!",
								"rank" => $this->args['srank'],
								"login_panel" => $this->login_panel->getData($this->args['sname'])
						));
					}
				} else {
					header("Location: /");
				}
			break;
			case "list":
				$users = $this->pdo->listUsers();
				$table = new \Service\Table();
				$table->addCell("Użytkownik", "black", null);
				$table->addCell("Status", "black", null);
				$table->nextRow();
				foreach ($users as $user) {
					$table->addCell($user->getNick(),"black","/user/show/".$user->getId());
					if($user->getStatus()=="Online")
						$table->addCell($user->getStatus(), "green", null);
					else
						$table->addCell($user->getStatus(), "red", null);
					$table->nextRow();
				}
				
				echo $this->twig->render('Index.html.twig', array(
						"menus_left" => $this->menu->makeMenu("left",$this->args['srank']),
						"table" => $table,
						"login_panel" => $this->login_panel->getData($this->args['sname'])
				));
			break;
			case "change_data":
				if(isset($_SESSION['id'])) {
					if($_POST['id']==$_SESSION['id']) {
						if($this->pdo->changeUserPassword($_POST['id'],$_POST['password'])) {
							session_unset();
							session_destroy();
							header("refresh:2;url=/");
							echo $this->twig->render('Index.html.twig', array(
									"menus_left" => $this->menu->makeMenu("left", $this->args['srank']),
									"message" => "Hasło zmieniono pomyślnie. Teraz nastąpi wylogowanie.",
									"login_panel" => $this->login_panel->getData($this->args['sname'])	
							));
						} else {
							header("refresh:2;url=/");
							echo $this->twig->render('Index.html.twig', array(
									"menus_left" => $this->menu->makeMenu("left", $this->args['srank']),
									"message" => "Wystąpił błąd!",
									"login_panel" => $this->login_panel->getData($this->args['sname'])
							));
						}
					}
				} 
				header("Location: /");
			break;
		}
	}
}