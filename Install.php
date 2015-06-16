<?php

use \Service\Json as Json;
use \Model\Menu as Menu;
use \Model\WidgetClock as Clock;


class Install {
	
	public function __construct() {
	}
	
	public function install() {
		var_dump($_POST);
		if(isset($_POST['dbserver']) && isset($_POST['dbname']) && isset($_POST['dblogin']) && isset($_POST['dbpass']) && isset($_POST['dbprefix']) && isset($_POST['nick']) && isset($_POST['email']) && isset($_POST['password'])) {
		$this->dbserver = $_POST['dbserver'];
		$this->dblogin = $_POST['dblogin'];
		$this->dbname = $_POST['dbname'];
		$this->dbpass = $_POST['dbpass'];
		$this->dbprefix = $_POST['dbprefix'];
		$this->createConfigFile();
		$this->createDBtable();
		$this->registerUser($_POST['nick'], $_POST['password'], $_POST['email']);
		
		
		
		$json = new Json();
		$json->open("config.json");
		
		$routings = array();
		$routings['showPost'] = '/post/{id}';
		$routings['showPosts'] = '/show/{order}';
		$routings['modifyPost'] = '/post/{action}/{id}';
		$routings['modifyComment'] = '/comment/{action}/{id}';
		$routings['login'] = '/login';
		$routings['logout'] = '/logout';
		$routings['register'] = '/register';
		$routings['users'] = '/user/{action}';
		$routings['user'] = '/user/{action}/{id}';
		$routings['admin'] = '/admin/{action}';
		$routings['easterEgg'] = '/easter';
		$routings['ajax'] = '/ajax/{action}';
		$routings['info'] = '/info/{action}';
		
		$json->put("routings", $routings);
		
		$menu = new Menu(3,"Menu główne");
		$menu->addItem("Strona główna","/",3);
		$menu->addItem("Użytkownicy","/user/list",2);
		$menu_left[] = $menu;
		$about = new Menu(3,"O nas");
		$about->addItem("Autorzy", "/info/author",3);
		$admin_menu = new Menu(1,"Menu Admina");
		$admin_menu->addItem("Dodaj post", "/post/add/0",1);
		$admin_menu->addItem("Użytkownicy", "/admin/list",1);
		$admin_menu->addItem("Edytuj menu", "/admin/edit_menu", 1);
		$admin_menu->addItem("Zarządzanie stroną", "/admin/panel", 1);
		$clock = new Clock(3);
		$menu_left[] = $admin_menu;
		$menu_right[] = $clock;
		$menu_right[] = $about;
		$json->put("widgets_left", $menu_left);
		$json->put("widgets_right", $menu_right);
		$json->put("title", $_POST['pagename']);
		$json->put("background", "/CSS/background.jpg");
		$json->put("favicon", "");
		$json->put("logo", "");
		$json->save();
		
		$hta = fopen(".htaccess", "a+");
		fwrite($hta, "
RewriteEngine on

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

RewriteRule ^(.*)$ index.php [L,QSA]");
		fclose($hta);
		
		$lock = fopen("installLock.php", "a+");
		fclose($lock);
		
		$this->addPost();
		echo "Instalacja pomyślna!";
			//header("index.php");
		} else {
			echo "Puste pola!";
			//header("index.php");
		}
	}
	
	public function createConfigFile() {
		$dbconf = fopen("mysqlpass.php", "w") or die("Błąd w zapisie pliku konfiguracyjnego!");
		$write =
		"<?php
define('MYSQL_SERVER','$this->dbserver');
define('MYSQL_LOGIN','$this->dblogin');
define('MYSQL_PASSWORD','$this->dbpass');
define('MYSQL_DATABASE','$this->dbname');
define('MYSQL_PREFIX','$this->dbprefix');
?>";
		fwrite($dbconf,$write);
		fclose($dbconf);
	}
	
	public function createDBtable() {
		$dbserver = $this->dbserver;
		$dbprefix = $this->dbprefix;
		try {
			$dbcon = new PDO("mysql:host=$this->dbserver;dbname=$this->dbname", $this->dblogin, $this->dbpass);
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$query = "CREATE TABLE $dbprefix"."_users (id INT(6) NOT NULL auto_increment, nick VARCHAR(30) NOT NULL, email VARCHAR(30) NOT NULL, password VARCHAR(100) NOT NULL, salt VARCHAR(100) NOT NULL, last_login DATETIME NOT NULL, rank INT(1) NOT NULL, lvl INT(3) NOT NULL, PRIMARY KEY (id),UNIQUE id (id));";
			$return = $dbcon->exec($query);
			$query = "CREATE TABLE $dbprefix"."_news (id INT(6) NOT NULL auto_increment, text LONGTEXT NOT NULL, title VARCHAR(50) NOT NULL, type VARCHAR(10) NOT NULL, author VARCHAR(100) NOT NULL, date DATETIME NOT NULL, PRIMARY KEY (id),UNIQUE id (id));";
			$return = $dbcon->exec($query);
			$query = "CREATE TABLE $dbprefix"."_comments (id INT(6) NOT NULL auto_increment, text LONGTEXT NOT NULL, author INT(6) NOT NULL, post INT(6) NOT NULL, date DATETIME NOT NULL, PRIMARY KEY (id),UNIQUE id (id),FOREIGN KEY (author) REFERENCES $dbprefix"."_users(id));";
			$return = $dbcon->exec($query);
			$dbcon = null;
			return true;
		} catch(PDOException $e) {
			return "Błąd: " . $e->getMessage();
		}
	}
	
	public function registerUser($nick,$pass,$email) {
		$dbserver = $this->dbserver;
		$dbprefix = $this->dbprefix;
		try {
			$dbcon = new PDO("mysql:host=$this->dbserver;dbname=$this->dbname", $this->dblogin, $this->dbpass);
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$query = $dbcon->prepare("SELECT id,nick FROM ".$this->prefix."_users WHERE nick = :nick");
			$query->bindValue(':nick', $nick);
			$query-> execute();
			$fetch = $query->fetch();
			if(empty($fetch)) {
				$salt = uniqid(mt_rand(), true);
				$password = hash('sha256', $salt . $pass);
				$query = $dbcon->prepare("INSERT INTO ".$this->prefix."_users VALUES ('',?,?,?,?,NOW(),?,'1')");
				$return = $query->execute(array($nick, $email, $password, $salt, 2));
				$this->loginUser($nick, $pass);
				$dbcon = null;
				return true;
			} else {
				$dbcon = null;
				return false;
			}
		} catch(\PDOException $e) {
			echo "Błąd: " . $e->getMessage();
		}
	}
	
	public function addPost() {
		$dbserver = $this->dbserver;
		$dbprefix = $this->dbprefix;
		try {
			$dbcon = new PDO("mysql:host=$this->dbserver;dbname=$this->dbname", $this->dblogin, $this->dbpass);
			$dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$query = $this->dbcon->prepare("INSERT INTO ".$this->prefix."_news (id, title, text, type, date, author) VALUES (NULL, :title, :text, \"post\", NOW(), :author);");
			$query->bindValue(":title","Witaj!");
			$query->bindValue(":text","Witaj na nowo utworzonej stronie! To jest przykładowy post. Możesz go edytować, usunąć, i dodawać swoje posty! Skonfiguruj stronę pod swoje upodobania klikając w linki po lewej!");
			$query->bindValue(":author",0);
			$query->execute();
			$fetch=$query->rowCount();
			if($fetch==1) {
				return true;
			} else {
				return false;
			}
		} catch(\PDOException $e) {
			echo "Błąd: " . $e->getMessage();
		}
	}
}