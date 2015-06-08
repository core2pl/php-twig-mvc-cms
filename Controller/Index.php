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
			if($this->args['srank']>3) {
				echo $this->renderPage("message", "Masz bana!");
			}
		} else
			$this->args['srank'] = 3;
		
		
	}
	
	public function showPosts($vars) {
		if($this->args['srank']>3) return;
		if(isset($_POST['order'])) {
			header("Location: /show/".$_POST['order']);
			return;
		}
		echo $this->renderPage("main_page", $this->pdo->getPosts($vars['order']));
	}
	
	public function easterEgg($vars) {
		if($this->args['srank']>3) return;
			setcookie("easter","easter",time() + 60);
			echo $this->renderPage("main_page", $this->pdo->getPosts("date"));
	}
	
	public function showPost($vars) {
		if($this->args['srank']>3) return;
		echo $this->renderPage("post", $this->pdo->getPost($vars['id']));
	}
	
	public function modifyComment($vars) {
		if($this->args['srank']>3) return;
		switch ($vars['action']) {
			case 'add':
				if (isset($_POST['text']) && isset($vars['id']) && isset($_SESSION['id']) && $this->args['srank']<=2) {
					if($this->pdo->addComment(new \Model\Comment(null, $_POST['text'], $_SESSION['id'], null, $vars['id'], null))) {
						header("Location: /post/".$vars['id']);
					} else {
						echo $this->renderPage("message", "Wystąpił błąd!");
					}
				} else {
					header("refresh: 2;url=/post/".$vars['id']);
					echo $this->renderPage("message", "Wystąpił błąd!");
				}
			break;
			case 'remove':
				if (isset($vars['id']) && isset($_POST['com_id']) && isset($_SESSION['id']) && $this->args['srank'] == 1) {
					if($this->pdo->removeComment($_POST['com_id'])) {
						header("refresh: 2;url=/post/".$vars['id']);
						echo $this->renderPage("message", "Komentarz usunięto pomyślnie!");
					} else {
						header("refresh: 2;url=/post/".$vars['id']);
						echo $this->renderPage("message", "Wystąpił błąd!");
					}
				} else {
					header("refresh: 2;url=/post/".$this->args['only']);
					echo $this->renderPage("message", "Wystąpił błąd!");
				}
			break;
		}
	}
	
	public function modifyPost($vars) {
		if($this->args['srank']>3) return;
		switch ($vars['action']) {
			case 'remove':
				if (isset($vars['id']) && isset($_SESSION['id']) && $this->args['srank'] == 1) {
					if($this->pdo->removePost($vars['id'])) {
						header("refresh: 2;url=/");
						echo $this->renderPage("message", "Post usunięto pomyślnie!");
					} else {
						header("refresh: 2;url=/");
						echo $this->renderPage("message", "Wystąpił błąd!");
					}
				} else {
					header("refresh: 2;url=/");
					echo $this->renderPage("message", "Wystąpił błąd!");
				}
			break;
			case 'add':
				if (isset($_POST['title']) && isset($_POST['text']) && isset($_SESSION['id']) && $this->args['srank'] == 1) {
					if($this->pdo->addPost(new \Model\Post(null, $_POST['title'], $_POST['text'], null, $_SESSION['id']))) {
						header("Location: /");
					} else {
						header("refresh: 2;url=/");
						echo $this->renderPage("message", "Wystąpił błąd!");
					}
				} else {
					$form = new \Service\Form("/post/add/0", "POST", "center", "width: 100%");
					$form->addInput("input","text", "title", "Tytuł");
					$form->addInput("textarea","text", "text", "Tekst");
					echo $this->renderPage("form", $form);
				}
			break;
			case 'edit':
				if (isset($_POST['title']) && isset($_POST['text']) && isset($vars['id']) && isset($_SESSION['id']) && $this->args['srank'] == 1) {
					if($this->pdo->editPost(new \Model\Post($vars['id'], $_POST['title'], $_POST['text'], null, $_SESSION['id']))) {
						header("Location: /post/".$vars['id']);
					} else {
						header("refresh:2;url=/post/edit/".$vars['id']);
						echo $this->renderPage("message", "Wystąpił błąd!");
					}
				} else if(isset($_SESSION['id']) && $this->args['srank'] == 1) {
					$value = $this->pdo->getPost($vars['id']);
					$form = new \Service\Form("/post/edit/".$vars['id'], "POST", "center", "width: 100%");
					$form->addInput("input","text", "title", "Tytuł","off", $value->getTitle());
					$form->addInput("textarea","text", "text", "Tekst","off", $value->getText());
					echo $this->renderPage("form", $form);
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
		if($this->args['srank']>3) return;
		if (isset($_POST['nick']) && isset($_POST['password']) && !isset($_SESSION['id'])) {
			$login = $this->pdo->loginUser($_POST['nick'], $_POST['password']);
			if($login==0) {
				header("Location: /");
			} else if($login==1) {
				header("refresh:2;url=/login");
				echo $this->renderPage("message", "Złe hasło!");
			} else if($login==2) {
				header("refresh:2;url=/login");
				echo $this->renderPage("message", "Zły login!");
			}
		} else if(isset($_SESSION['id'])) {
			header("refresh:2;url=/");
			echo $this->renderPage("message", "Jesteś już zalogowany!");
		} else {
			$form = new \Service\Form("/login", "POST");
			$form->addInput("input","text", "nick", "Nick (>5 znaków, bez liczb):");
			$form->addInput("input","password", "password", "Hasło (>5 znaków, bez liczb):");
			echo $this->renderPage("form", $form);
		}
	}
	
	public function logout() {
		session_unset();
		session_destroy();
		header("Location: /");
	}
	
	public function register($vars) {
		if($this->args['srank']>3) return;
		if (isset($_POST['nick']) && isset($_POST['password']) && isset($_POST['password2']) 
				&& isset($_POST['email']) && !isset($_SESSION['id'])) {	
			if($_POST['password']!=$_POST['password2']) {
				header("refresh:2;url=/register");
				echo $this->renderPage("message", "Hasła się różnią!");
				return;
			}
			if($this->pdo->registerUser($_POST['nick'], $_POST['password'],$_POST['email'])) {
				header("refresh:2;url=/");
				echo $this->renderPage("message", "Rejestracja pomyślna!");
			} else  {
				header("refresh:2;url=/register");
				echo $this->renderPage("message", "Taki nick już istnieje!");
			}
		} else if(isset($_SESSION['id'])) {
			header("refresh:2;url=/");
			echo $this->renderPage("message", "Jesteś już zalogowany!");
		} else {
			$form = new \Service\Form("/register", "POST");
			$form->addInput("input","text", "nick", "Nick (>5 znaków, bez liczb):");
			$form->addInput("input","text", "email", "Email (zmyślony):");
			$form->addInput("input","password", "password", "Hasło (>5 znaków, bez liczb):");
			$form->addInput("input","password", "password2", "Potwierdź Hasło (>5 znaków, bez liczb):");
			echo $this->renderPage("form", $form);
		}
	}
	
	public function users($vars) {
		if($this->args['srank']>3) return;
		$this->user($vars);
	} 
	
	public function user($vars) {
		if($this->args['srank']>3) return;
		switch ($vars['action']) {
			case "show":
			echo $this->renderPage("user", $this->pdo->getUserData($vars['id']));
			break;
			case "remove":
				if(isset($_SESSION['id']) && $this->args['srank'] == 1 && isset($vars['id'])) {
					if($this->pdo->removeUser($vars['id'])) {
						header("refresh:2;url=/");
						echo $this->renderPage("message", "Usunięto użytkownika pomyślnie!");
					} else {
						header("refresh:2;url=/");
						echo $this->renderPage("message", "Wystąpił błąd!");
					}
				} else {
					header("Location: /");
				}
			break;
			case "ban":
				if(isset($_SESSION['id']) && $this->args['srank'] == 1) {
					if($this->pdo->banUser($vars['id'])) {
						header("refresh:2;url=/");
						echo $this->renderPage("messsage", "Zbanowano użytkownika pomyślnie!");
					} else {
						header("refresh:2;url=/");
						echo $this->renderPage("message", "Wystąpił błąd!");
					}
				} else {
					header("Location: /");
				}
			break;
			case "list":
				if($this->args['srank']>2) {
					header("refresh:2;url=/");
					echo $this->renderPage("message", "Nie masz uprawnień do przeglądania tej strony!");
					return;
				}
				$users = $this->pdo->listUsers();
				$table = new \Service\Table();
				$table->setJs('
					function refreshList() {
						$.ajax({
									
							url : "/ajax.php",
							data: {
								"list": true
							},
							type : "get",
							success: function(data){
								$(".page_block").html(data);
							}
									
						});
					}
					window.setInterval("refreshList()", 30000);');
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
				
				echo $this->renderPage("table", $table);
			break;
			case "change_password":
				if(isset($_SESSION['id'])) {
					if($_POST['id']==$_SESSION['id']) {
						if($this->pdo->changeUserPassword($_POST['id'],$_POST['password'])) {
							session_unset();
							session_destroy();
							header("refresh:2;url=/");
							echo $this->renderPage("message", "Hasło zmieniono pomyślnie. Teraz nastąpi wylogowanie!");
						} else {
							header("refresh:2;url=/");
							echo $this->renderPage("message", "Wystąpił błąd!");
						}
					}
				} 
				header("Location: /");
			break;
			case "edit":
				if(isset($_SESSION['id']) && $this->args['srank']==1 && isset($vars['id'])) {
					if(isset($_POST['nick'])) {
						if(empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['lvl']) || empty($_POST['rank'])) {
							header("refresh:2;url=/user/edit/".$vars['id']);
							echo $this->renderPage("message", "Bład! Uzupełnij wszyskie pola!");
							return;
						}
						if(!empty($_POST['password'])) {
							$this->pdo->changeUserPassword($vars['id'], $_POST['password']);
						}
						$user = $this->pdo->getUserData($vars['id']);
						$user->setNick($_POST['nick'])
							->setEmail($_POST['email'])
							->setLvl($_POST['lvl'])
							->setRank($_POST['rank']);
						if($this->pdo->change_data($user)) {
							header("Location: /user/show/".$vars['id']);
						} else {
							header("refresh:2;url=/user/edit/".$vars['id']);
							echo $this->renderPage("message", "Wystąpił błąd!");
						}
					} else {
						$user = $this->pdo->getUserData($vars['id']);
						$form = new \Service\Form("/user/edit/".$vars['id'], "POST");
						$form->addInput("input", "text", "nick", "Nick: ".$user->getNick()."</br>Nowy nick:","off",$user->getNick())
							->addInput("input", "text", "email", "</br>Email: ".$user->getEmail()."</br>Nowy email:","off",$user->getEmail())
							->addInput("input", "text", "lvl", "</br>Lvl: ".$user->getLvl()."</br>Nowy lvl:","off",$user->getLvl())
							->addInput("select", null, "rank", "</br>Ranga: ".$user->getRankName()."</br>Nowa ranga:","off",array(
									"1" => "Administrator",
									"2" => "Użytkownik",
									"4" => "Zbanowany"
							))
							->addInput("input", "password", "password", "Nowe hasło:");
						echo $this->renderPage("form", $form);
					}
				} else {
					header("Location: /");
				}
			break;
		}
	}
	
	public function renderPage($type,$value) {
		$this->json->open("config.json");
		if(isset($_COOKIE['easter'])) {
			return $this->twig->render('Index.html.twig', array(
				"menus_top" => $this->menu->makeMenu("top", $this->args['srank']),
				"menus_left" => $this->menu->makeMenu("left", $this->args['srank']),
				"menus_right" => $this->menu->makeMenu("right", $this->args['srank']),
				"menus_footer" => $this->menu->makeMenu("footer", $this->args['srank']),
				$type => $value,
				"easter" => true,
				"background" => $this->json->get("background"),
				"favicon" => $this->json->get("favicon"),
				"title" => $this->json->get("title"),
				"rank" => $this->args['srank'],
				"login_panel" => $this->login_panel->getData($this->args['sname'])
			));
		} else {
			return $this->twig->render('Index.html.twig', array(
				"menus_top" => $this->menu->makeMenu("top", $this->args['srank']),
				"menus_left" => $this->menu->makeMenu("left", $this->args['srank']),
				"menus_right" => $this->menu->makeMenu("right", $this->args['srank']),
				"menus_footer" => $this->menu->makeMenu("footer", $this->args['srank']),
				$type => $value,
				"background" => $this->json->get("background"),
				"favicon" => $this->json->get("favicon"),
				"title" => $this->json->get("title"),
				"rank" => $this->args['srank'],
				"login_panel" => $this->login_panel->getData($this->args['sname'])
			));
		} 
	}
	
	public function admin($vars) {
		if($this->args['srank']>3) return;
		if($this->args['srank']>1) {
			header("refresh:2;url=/");
			echo $this->renderPage("message", "Nie masz uprawnień do przeglądania tej strony!");
			return;
		}
		switch ($vars['action']) {
			case "edit_menu":
				if(isset($_POST['edit'])) {
					if($_POST['place'] == "left" || $_POST['place'] == "right" || $_POST['place'] == "top" || $_POST['place'] == "footer") {
						$this->json->open("config.json");
						$menus = $this->json->get("menu_".$_POST['place']);
						if(isset($menus[$_POST['place']])) {
							$menu = $this->json->get("menu_".$_POST['place'])[$_POST['id']];
							if($_POST['type'] == "item") $menu = $this->json->get("menu_".$_POST['place'])[$_POST['menu']];
						} else {
							header("refresh:2;url=/admin/edit_menu");
							echo $this->renderPage("message", "Błędne id menu!");
							return;
						}
					} else {
						header("refresh:2;url=/admin/edit_menu");
						echo $this->renderPage("message", "Błędna pozycja!");
						return;
					}
					switch ($_POST['edit']) {
						case "name":
							if($_POST['type'] == "menu") {
								if($_POST['newname']!=$menu->getName()) {
									$menu->setName($_POST['newname']);
									$menus[$_POST['id']] = $menu;
									$this->json->put("menu_".$_POST['place'],$menus);
									$this->json->save();
									header("Location: /admin/edit_menu");
								}
							} else {
								if($_POST['newname']!=$menu->getItem($_POST['id'], "name")) {
									$menu->modifyItem($_POST['id'],"name",$_POST['newname']);
									$menus[$_POST['menu']] = $menu;
									$this->json->put("menu_".$_POST['place'],$menus);
									$this->json->save();
									header("Location: /admin/edit_menu");
								}
							}
						break;
						case "rank":
							if($_POST['type'] == "menu") {
								if($_POST['newrank']!=$menu->getRank()) {
									$menu->setRank($_POST['newrank']);
									$menus[$_POST['id']] = $menu;
									$this->json->put("menu_".$_POST['place'],$menus);
									$this->json->save();
									header("Location: /admin/edit_menu");
								}
							} else {
								if($_POST['newrank']!=$menu->getItem($_POST['id'],"rank")) {
									$menu->modifyItem($_POST['id'],"rank",$_POST['newrank']);
									$menus[$_POST['menu']] = $menu;
									$this->json->put("menu_".$_POST['place'],$menus);
									$this->json->save();
									header("Location: /admin/edit_menu");
								}
							}
							break;
						case "place":
							if($_POST['newplace']!=$_POST['place']) {
								$menus_new = $this->json->get("menu_".$_POST['newplace']);
								$menus_new[] = $menus[$_POST['id']];
								if($_POST['id']<=(sizeof($menus)-1)) {
									for($i = $_POST['id']; $i < sizeof($menus)-1; $i++) {
										echo $menus[$i]->renderMenu(1)->name;
										$menus[$i] = $menus[$i+1];
									}
									unset($menus[sizeof($menus)-1]);
								}
								$this->json->put("menu_".$_POST['newplace'],$menus_new);
								$this->json->put("menu_".$_POST['place'],$menus);
								$this->json->save();
								header("Location: /admin/edit_menu");
							}
						break;
						case "add":
							switch ($_POST['type']) {
								case "menu":
									$menu = new \Model\Menu($_POST['name'], $_POST['rank']);
									$menus[] = $menu;
									$this->json->put("menu_".$_POST['place'],$menus);
									$this->json->save();
									header("Location: /admin/edit_menu");
								break;
								case "item":
									$menu->addItem($_POST['name'], $_POST['href'], $_POST['rank']);
									$menus[$_POST['menu']] = $menu;
									$this->json->put("menu_".$_POST['place'],$menus);
									$this->json->save();
									header("Location: /admin/edit_menu");
								break;
							}
						break;
					}
					/*if($_POST['type']=="menu") {
						$this->json->open("config.json");
						$menus = $this->json->get("menu_".$_POST['place']);
						$menu = $this->json->get("menu_".$_POST['place'])[$_POST['id']];
						if($_POST['new_place']!=$_POST['place']) {
							$menus_new = $this->json->get("menu_".$_POST['new_place']);
							$menus_new[] = $menus[$_POST['id']];
							if($_POST['id']<=(sizeof($menus)-1)) {
								for($i = $_POST['id']; $i < sizeof($menus)-1; $i++) {
									echo $menus[$i]->renderMenu(1)->name;
									$menus[$i] = $menus[$i+1];
								}	
								unset($menus[sizeof($menus)-1]);
							}
							$this->json->put("menu_".$_POST['new_place'],$menus_new);
							$this->json->save();
							$this->json->put("menu_".$_POST['place'],$menus);
							$this->json->save();
							header("Location: /admin/edit_menu");
							return;
						}
						$menus[$_POST['id']] = $menu;
						if($_POST['new_id']!=$_POST['id']) {
							if($_POST['new_id']<sizeof($menus))
							if($_POST['new_id'] > $_POST['id']) {
								$temp = $menu;
								for($i = $_POST['id']; $i < $_POST['new_id']; $i++) {
									$menus[$i] = $menus[$i+1];
								}
								$menus[$_POST['new_id']] = $temp;
							} else {
								$temp = $menu;
								for($i = $_POST['id']; $i > $_POST['new_id']; $i--) {
									$menus[$i] = $menus[$i-1];
								}
								$menus[$_POST['new_id']] = $temp;
							}
						} 
					}
				} elseif(isset($_POST['remove'])) {
					if($_POST['type']=="menu") {
						$this->json->open("config.json");
						$menus = $this->json->get("menu_".$_POST['place']);
						if($_POST['id']<=(sizeof($menus)-1)) {
							for($i = $_POST['id']; $i < sizeof($menus)-1; $i++) {
								echo $menus[$i]->renderMenu(1)->name;
								$menus[$i] = $menus[$i+1];
							}	
							unset($menus[sizeof($menus)-1]);
						}
					} else {
						
					}
					$this->json->put("menu_".$_POST['place'],$menus);
					$this->json->save();
					header("Location: /admin/edit_menu");
				} elseif(isset($_POST['add'])) {
					if($_POST['add']=="create") {
						$menu = new \Model\Menu($_POST['name'], $_POST['rank']);
					} else {
						$form = new \Service\Form("/admin/edit_menu", "POST");
						$form->addInput("input", "text", "name", "Tytuł menu:");
						$this->renderPage("form", $form);
					}*/
				} elseif (isset($_POST['add'])) {
					switch ($_POST['add']) {
						case "menu":
							$form = new \Service\Form("/admin/edit_menu", "POST");
							$form->addInput("input", "text", "name", "Tytuł menu:","off","Moje menu");
							$form->addInput("input", "text", "rank", "Ranga menu:","off","3");
							$form->addInput("input", "text", "place", "Pozycja","off","left");
							$form->addInput("input", "hidden", "edit", null, "off", "add");
							$form->addInput("input", "hidden", "type", null, "off", "menu");
							echo $this->renderPage("form", $form);
						break;
						case "item":
							$form = new \Service\Form("/admin/edit_menu", "POST");
							$form->addInput("input", "text", "name", "Nazwa elementu:","off","Mój link");
							$form->addInput("input", "text", "href", "Adres elementu:","off","/show/date");
							$form->addInput("input", "text", "rank", "Ranga elementu:","off","3");
							$form->addInput("input", "text", "menu", "Id menu:","off","0");
							$form->addInput("input", "text", "place", "Pozycja","off","left");
							$form->addInput("input", "hidden", "edit", null, "off", "add");
							$form->addInput("input", "hidden", "type", null, "off", "item");
							echo $this->renderPage("form", $form);
						break;
					}
				} else {
					if($_POST['type']=="menu") {
						$this->json->open("config.json");
						$menu = $this->json->get("menu_".$_POST['place'])[$_POST['id']];
						$editmenu = (object)null;
						$editmenu->type = $_POST['type'];
						$editmenu->id = $_POST['id'];
						$editmenu->place = $_POST['place'];
						$editmenu->rank = $menu->getRank();
						$editmenu->name = $menu->getName();
					} elseif($_POST['type']=="item") {
						$this->json->open("config.json");
						$menu = $this->json->get("menu_".$_POST['place'])[$_POST['menu']];
						$editmenu = (object)null;
						$editmenu->type = $_POST['type'];
						$editmenu->id = $_POST['id'];
						$editmenu->menu = $_POST['menu']; 
						$editmenu->place = $_POST['place'];
						$editmenu->rank = $menu->getItem($_POST['id'], "rank");
						$editmenu->name = $menu->getItem($_POST['id'], "name");
					}
					echo $this->renderPage("edit_menu", $editmenu);
				}
			break;
			case "panel":
				if(isset($_POST['edit'])) {
					$this->json->open("config.json");
					$this->json->put("title", $_POST['title']);
					$this->json->put("background", $_POST['background']);
					$this->json->put("favicon", $_POST['favicon']);
					$this->json->save();
					header("Location: /admin/panel");
				} else {
					$this->json->open("config.json");
					$form = new \Service\Form("/admin/panel", "POST");
					$form->addInput("input", "text", "title", "Tytuł Strony:","off",$this->json->get("title"));
					$form->addInput("input", "text", "background", "Adres do tła strony:","off",$this->json->get("background"));
					$form->addInput("input", "text", "favicon", "Adres do ikony strony:","off",$this->json->get("favicon"));
					$form->addInput("input", "hidden", "edit", null,"off", true);
					echo $this->renderPage("form", $form);
				}
			break;
			case "list":
				$users = $this->pdo->listUsers();
				$table = new \Service\Table();
				$table->setJs('
					function refreshList() {
						$.ajax({
					
							url : "/ajax.php",
							data: {
								"admin_list": true
							},
							type : "get",
							success: function(data){
								$(".page_block").html(data);
							}
					
						});
					}
					window.setInterval("refreshList()", 30000);');
				$table->addCell("Użytkownik", "black", null);
				$table->addCell("Ranga", "black", null);
				$table->addCell("Status", "black", null);
				$table->nextRow();
				foreach ($users as $user) {
					$table->addCell($user->getNick(),"black","/user/show/".$user->getId());
					switch ($user->getRank()) {
						case 1:
							$table->addCell("Admin", "red", null);
						break;
						case 2:
							$table->addCell("Użytkownik", "green", null);
						break;
						case 4:
							$table->addCell("Zbanowany", "black", null);
						break;
					}
					if($user->getStatus()=="Online")
						$table->addCell($user->getStatus(), "green", null);
					else
						$table->addCell($user->getStatus(), "red", null);
					$table->nextRow();
				}
				
				echo $this->renderPage("table", $table);
			break;
		}
	}
}