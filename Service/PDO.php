<?php
namespace Service;

use Model\Post as Post;

class PDO {
	
	private $dbcon;
	private $server;
	private $database;
	private $prefix;
	
	public function __construct() {
		try {
			$this->server = MYSQL_SERVER;
			$this->database = MYSQL_DATABASE;
			$this->prefix = MYSQL_PREFIX;
			$this->dbcon = new \PDO("mysql:host=$this->server;dbname=$this->database", MYSQL_LOGIN, MYSQL_PASSWORD);
			$this->dbcon->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		} catch(\PDOException $e) {
			echo "Błąd: " . $e->getMessage();
		}
	}
	
	public function getPosts($order = "date") {
		try {
			$query = $this->dbcon->prepare("SELECT * FROM ".$this->prefix."_news ORDER BY ".$order." DESC");
			$query->execute();
			$fetch=$query->fetchAll(\PDO::FETCH_ASSOC);
			if(!empty($fetch)) {
				$posts = array();
				foreach ($fetch as $post) {
					$temp = new Post($post['id'],$post['title'],$post['text'],$post['date'],$post['author']);
					$temp->setAuthor($this->getUserName($temp->getAuthorId()));
					$posts[] = $temp;
				}
				return $posts;
			} else {
				return false;
			}
		} catch(\PDOException $e) {
			echo "Błąd: " . $e->getMessage();
		}
	}
	
	public function getPost($id) {
		try {
			$query = $this->dbcon->prepare("SELECT * FROM ".$this->prefix."_news WHERE id = ".$id);
			$query->execute();
			$fetch=$query->fetchAll(\PDO::FETCH_ASSOC);
			if(!empty($fetch)) {
				$posts = array();
				foreach ($fetch as $post) {
					$temp = new Post($post['id'],$post['title'],$post['text'],$post['date'],$post['author']);
					$temp->setAuthor($this->getUserName($temp->getAuthorId()));
					$posts[] = $temp;
				}
				return $posts;
			} else {
				return false;
			}
		} catch(\PDOException $e) {
			return "Błąd: " . $e->getMessage();
		}
	}
	
	
	public function addPost($title,$text,$author) {
		try {
			$query = $this->dbcon->prepare("INSERT INTO ".$this->prefix."_news (id, title, text, type, date, author) VALUES (NULL, :title, :text, \"post\", NOW(), :author);");
			$query->bindValue(":title",$title);
			$query->bindValue(":text",$text);
			$query->bindValue(":author",$author);
			$query->execute();
			$fetch=$query->rowCount();
			if($fetch==1) {
				return true;
			} else {
				return false;
			}
		} catch(\PDOException $e) {
			return "Błąd: " . $e->getMessage();
		}
	}
	
	public function editPost($id) {
		try {
			$query = $this->dbcon->prepare("UPDATE ".$this->prefix."_news SET title = :newtitle,text = :newtext, date = Now() WHERE id = :id");
			$query->bindValue(":newtitle",$_POST['title']);
			$query->bindValue(":newtext",$_POST['text']);
			$query->bindValue(":id",$id);
			$query->execute();
			$fetch=$query->rowCount();
			if($fetch==1) {
				return  true;
			} else {
				return false;
			}
		} catch(\PDOException $e) {
			return "Błąd: " . $e->getMessage();
		}
	}
	
	public function removePost($id) {
		try {
			$query = $this->dbcon->prepare("DELETE FROM ".$this->prefix."_news WHERE id = :id");
			$query->bindValue(":id",$id);
			$query->execute();
			$fetch=$query->rowCount();
			if($fetch==1) {
				return  true;
			} else {
				return false;
			}
		} catch(\PDOException $e) {
			return "Błąd: " . $e->getMessage();
		}
	}
	
	public function getComments($post_id) {
		try {
			$query = $this->dbcon->prepare("SELECT ".$this->prefix."_comments.id, ".$this->prefix."_comments.text, ".$this->prefix."_comments.author, ".$this->prefix."_comments.post, ".$this->prefix."_comments.date, ".$this->prefix."_users.nick FROM ".$this->prefix."_comments INNER JOIN ".$this->prefix."_users ON ".$this->prefix."_comments.author=".$this->prefix."_users.id WHERE ".$this->prefix."_comments.post=".$post_id." ORDER BY ".$this->prefix."_comments.date DESC;");
			$query->execute();
			$fetch=$query->fetchAll(\PDO::FETCH_ASSOC);
			if(!empty($fetch)) {
				return $fetch;
			} else {
				return false;
			}
		} catch(\PDOException $e) {
			return "Błąd: " . $e->getMessage();
		}
	}
	
	public function createComment($text,$post_id,$author) {
		try {
			$query = $this->dbcon->prepare("INSERT INTO ".$this->prefix."_comments (id, text, author, post, date) VALUES (NULL, :text, :author, :post, NOW());");
			$query->bindValue(":text",$text);
			$query->bindValue(":post",$post_id);
			$query->bindValue(":author",$author);
			$query->execute();
			$fetch=$query->rowCount();
			if($fetch==1) {
				return true;
			} else {
				return false;
			}
		} catch(\PDOException $e) {
			return "Błąd: " . $e->getMessage();
		}
	}
	
	public function removeComment($id,$post_id) {
		try {
			$query = $this->dbcon->prepare("DELETE FROM ".$this->prefix."_comments WHERE id = :id");
			$query->bindValue(":id",$id);
			$query->execute();
			$fetch=$query->rowCount();
			if($fetch==1) {
				return  header("Location: ?action=show&only=".$post_id);
			} else {
				return false;
			}
		} catch(\PDOException $e) {
			return "Błąd: " . $e->getMessage();
		}
	}
	
	public function getUserName($userId) {
		try {
			$query = $this->dbcon->prepare("SELECT id,nick FROM ".$this->prefix."_users WHERE id = :id");
			$query->bindValue(":id",$userId);
			$query->execute();
			$fetch=$query->fetchAll(\PDO::FETCH_ASSOC);
			if(!empty($fetch)) {
				return $fetch[0]['nick'];
			} else {
				return false;
			}
		} catch(\PDOException $e) {
			echo "Błąd: " . $e->getMessage();
		}
	}
	
	public function removeUser($userId) {
		try {
			$query = $this->dbcon->prepare("DELETE FROM ".$this->prefix."_users WHERE id = :id");
			$query->bindValue(":id",$userId);
			$query->execute();
			$fetch=$query->rowCount();
			if($fetch==1) {
				return  true;
			} else {
				return false;
			}
		} catch(\PDOException $e) {
			echo "Błąd: " . $e->getMessage();
		}
	}
	
	public function userExists($userId) {
		try {
			$query = $this->dbcon->prepare("SELECT id FROM ".$this->prefix."_users WHERE id = :id");
			$query->bindValue(":id",$userId);
			$query->execute();
			$fetch=$query->fetch();
			if(!empty($fetch)) {
				return true;
			} else {
				return false;
			}
		} catch(\PDOException $e) {
			return "Błąd: " . $e->getMessage();
		}
	}
	
	public function getUserRank($userId) {
		try {
			$query = $this->dbcon->prepare("SELECT rank FROM ".$this->prefix."_users WHERE id = :id");
			$query->bindValue(":id",$userId);
			$query->execute();
			$fetch=$query->fetch();
			if(!empty($fetch)) {
				return $fetch['rank'];
			} else {
				return false;
			}
		} catch(\PDOException $e) {
			return "Błąd: " . $e->getMessage();
		}
	}
	
	public function setLastLogin($userId) {
		try {
			$query = $this->dbcon->prepare("UPDATE ".$this->prefix."_users SET last_login = NOW() WHERE id = :id");
			$query->bindValue(":id",$userId);
			$query->execute();
			$fetch=$query->fetch();
			if(!empty($fetch)) {
				return true;
			} else {
				return false;
			}
		} catch(\PDOException $e) {
			return "Błąd: " . $e->getMessage();
		}
	}
	
	public function getLastLogin($userId) {
		try {
			$query = $this->dbcon->prepare("SELECT id,last_login FROM ".$this->prefix."_users WHERE id = :id");
			$query->bindValue(":id",$userId);
			$query->execute();
			$fetch=$query->fetch();
			if(!empty($fetch)) {
				return $fetch;
			} else {
				return Array();
			}
		} catch(\PDOException $e) {
			return "Błąd: " . $e->getMessage();
		}
	}
	
	public function getUserData($userId) {
		try {
			$query = $this->dbcon->prepare("SELECT * FROM ".$this->prefix."_users WHERE id = :id");
			$query->bindValue(":id",$userId);
			$query->execute();
			$fetch=$query->fetch();
			if(!empty($fetch)) {
				return $fetch;
			} else {
				return null;
			}
		} catch(\PDOException $e) {
			return "Błąd: " . $e->getMessage();
		}
	}
	
	public function loginUser() {
		try {
			$query = $this->dbcon->prepare("SELECT id,nick,password,salt FROM ".$this->prefix."_users WHERE nick = :nick");
			$query->bindValue(":nick",$_POST['nick']);
			$query->execute();
			$fetch=$query->fetch();
			$dbcon = null;
			if(!empty($fetch)) {
				$password = $fetch['password'];
				$salt = $fetch['salt'];
				$password2 = hash('sha256', $salt . $_POST['password']);
				if($password == $password2) {
					$_SESSION['id']=$fetch['id'];
					return 0;
				} else {
					return 1;
				}
			} else {
				return 2;
			}
		} catch(PDOException $e) {
			echo "Błąd: " . $e->getMessage();
		}
	}
	
	public function closeConnection() {
		$this->dbcon=null;
	}
}