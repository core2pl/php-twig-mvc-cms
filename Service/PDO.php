<?php
namespace Service;

class PDO {
	
	private $dbcon;
	private $server;
	private $database;
	private $prefix;
	
	public function __construct($name) {
		$this->name = $name;
		try {
			$this->server = MYSQL_SERVER;
			$this->database = MYSQL_DATABASE;
			$this->prefix = MYSQL_PREFIX;
			$this->dbcon = new \PDO("mysql:host=$this->server;dbname=$this->database", MYSQL_LOGIN, MYSQL_PASSWORD);
			$this->dbcon->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
			echo "Błąd: " . $e->getMessage();
		}
	}
	
	public function getPosts($order = "date") {
		try {
			$query = $this->dbcon->prepare("SELECT * FROM $this->prefix"."_news ORDER BY ".$order." DESC");
			$query->execute();
			$fetch=$query->fetchAll(\PDO::FETCH_ASSOC);
			if(!empty($fetch)) {
				return $fetch;
			} else {
				return false;
			}
		} catch(PDOException $e) {
			echo "Błąd: " . $e->getMessage();
		}
	}
	
	public function getPost($id) {
		try {
			$query = $this->dbcon->prepare("SELECT * FROM $prefix"."_news WHERE id = ".$id);
			$query->execute();
			$fetch=$query->fetchAll(\PDO::FETCH_ASSOC);
			if(!empty($fetch)) {
				return $fetch;
			} else {
				return false;
			}
		} catch(PDOException $e) {
			return "Błąd: " . $e->getMessage();
		}
	}
	
	
	public function createPost($title,$text,$author) {
		try {
			$query = $this->dbcon->prepare("INSERT INTO $prefix"."_news (id, title, text, type, date, author) VALUES (NULL, :title, :text, \"post\", NOW(), :author);");
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
		} catch(PDOException $e) {
			return "Błąd: " . $e->getMessage();
		}
	}
	
	public function editPost($id) {
		try {
			$query = $this->dbcon->prepare("UPDATE $prefix"."_news SET title = :newtitle,text = :newtext, date = Now() WHERE id = :id");
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
		} catch(PDOException $e) {
			return "Błąd: " . $e->getMessage();
		}
	}
	
	public function removePost($id) {
		try {
			$query = $this->dbcon->prepare("DELETE FROM $prefix"."_news WHERE id = :id");
			$query->bindValue(":id",$id);
			$query->execute();
			$fetch=$query->rowCount();
			if($fetch==1) {
				return  true;
			} else {
				return false;
			}
		} catch(PDOException $e) {
			return "Błąd: " . $e->getMessage();
		}
	}
	
	public function getComments($post_id) {
		try {
			$query = $this->dbcon->prepare("SELECT $this->prefix"."_comments.id, $this->prefix"."_comments.text, $this->prefix"."_comments.author, $this->prefix"."_comments.post, $this->prefix"."_comments.date, $this->prefix"."_users.nick FROM $this->prefix"."_comments INNER JOIN $this->prefix"."_users ON $this->prefix"."_comments.author=$this->prefix"."_users.id WHERE $this->prefix"."_comments.post=".$post_id." ORDER BY $this->prefix"."_comments.date DESC;");
			$query->execute();
			$fetch=$query->fetchAll(\PDO::FETCH_ASSOC);
			if(!empty($fetch)) {
				return $fetch;
			} else {
				return false;
			}
		} catch(PDOException $e) {
			return "Błąd: " . $e->getMessage();
		}
	}
	
	public function createComment($text,$post_id,$author) {
		try {
			$query = $this->dbcon->prepare("INSERT INTO $prefix"."_comments (id, text, author, post, date) VALUES (NULL, :text, :author, :post, NOW());");
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
		} catch(PDOException $e) {
			return "Błąd: " . $e->getMessage();
		}
	}
	
	public function removeComment($id,$post_id) {
		try {
			$query = $this->dbcon->prepare("DELETE FROM $prefix"."_comments WHERE id = :id");
			$query->bindValue(":id",$id);
			$query->execute();
			$fetch=$query->rowCount();
			if($fetch==1) {
				return  header("Location: ?action=show&only=".$post_id);
			} else {
				return false;
			}
		} catch(PDOException $e) {
			return "Błąd: " . $e->getMessage();
		}
	}
	
	public function closeConnection() {
		$this->dbcon=null;
	}
}