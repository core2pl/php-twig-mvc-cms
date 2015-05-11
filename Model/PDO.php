<?php
namespace Model;

use Model\Base;

class PDO {
	
	private $name;
	private $dbcon;
	
	public function __construct($name) {
		$this->name = $name;
		try {
			$server = MYSQL_SERVER;
			$database = MYSQL_DATABASE;
			$prefix = MYSQL_PREFIX;
			$this->dbcon = new PDO("mysql:host=$server;dbname=$database", MYSQL_LOGIN, MYSQL_PASSWORD);
			$this->dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
			return "B��d: " . $e->getMessage();
		}
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getPosts() {
		try {
			$query = $this->dbcon->prepare("SELECT * FROM $prefix"."_news ORDER BY date DESC");
			$query->execute();
			$fetch=$query->fetchAll(PDO::FETCH_ASSOC);
			if(!empty($fetch)) {
				return $fetch;
			} else {
				return Array();
			}
		} catch(PDOException $e) {
			return "B��d: " . $e->getMessage();
		}
	}
	
	public function getPost() {
		try {
			$query = $this->dbcon->prepare("SELECT * FROM $prefix"."_news WHERE id = ".$type);
			$query->execute();
			$fetch=$query->fetchAll(PDO::FETCH_ASSOC);
			if(!empty($fetch)) {
				return $fetch;
			} else {
				return Array();
			}
		} catch(PDOException $e) {
			return "B��d: " . $e->getMessage();
		}
	}
	
	public function getComments() {
		try {
			$query = $this->dbcon->prepare("SELECT $prefix"."_comments.id, $prefix"."_comments.text, $prefix"."_comments.author, $prefix"."_comments.post, $prefix"."_comments.date, $prefix"."_users.nick FROM $prefix"."_comments INNER JOIN $prefix"."_users ON $prefix"."_comments.author=$prefix"."_users.id WHERE $prefix"."_comments.post=".$_GET['only']." ORDER BY $prefix"."_comments.date DESC;");
			$query->execute();
			$fetch=$query->fetchAll(PDO::FETCH_ASSOC);
			if(!empty($fetch)) {
				return $fetch;
			} else {
				return Array();
			}
		} catch(PDOException $e) {
			return "B��d: " . $e->getMessage();
		}
	}
	
	public function createPost() {
		try {
			$query = $this->dbcon->prepare("INSERT INTO $prefix"."_news (id, title, text, type, date, author) VALUES (NULL, :title, :text, \"post\", NOW(), :author);");
			$query->bindValue(":title",$_POST['title']);
			$query->bindValue(":text",$_POST['text']);
			$query->bindValue(":author",$_SESSION['id']);
			$query->execute();
			$fetch=$query->rowCount();
			if($fetch==1) {
				return "<script> location.replace(\"?action=show&order=date\"); </script>";
			} else {
				return "Wyst�pi� b��d</br><input type=\"button\" value=\"Wr��\" onclick=\"history.back(-1)\"/>";
			}
		} catch(PDOException $e) {
			return "B��d: " . $e->getMessage();
		}
	}
	
	public function removePost() {
		try {
			$query = $this->dbcon->prepare("DELETE FROM $prefix"."_news WHERE id = :id");
			$query->bindValue(":id",$_GET['id']);
			$query->execute();
			$fetch=$query->rowCount();
			if($fetch==1) {
				return  "<script> location.replace(\"?action=show&order=date\"); </script>";
			} else {
				return "Wyst�pi� b��d</br><input type=\"button\" value=\"Wr��\" onclick=\"history.back(-1)\"/>";
			}
		} catch(PDOException $e) {
			return "B��d: " . $e->getMessage();
		}
	}
	
	public function createComment() {
		try {
			$query = $dbcon->prepare("INSERT INTO $prefix"."_comments (id, text, author, post, date) VALUES (NULL, :text, :author, :post, NOW());");
			$query->bindValue(":text",$_POST['text']);
			$query->bindValue(":post",$_POST['id']);
			$query->bindValue(":author",$_SESSION['id']);
			$query->execute();
			$fetch=$query->rowCount();
			$dbcon = null;
			if($fetch==1) {
				return "<script> window.history.back(-1); </script>";
			} else {
				return "Wyst�pi� b��d</br><input type=\"button\" value=\"Wr��\" onclick=\"history.back(-1)\"/>";
			}
		} catch(PDOException $e) {
			return "B��d: " . $e->getMessage();
		}
	}
	
	public function closeConnection() {
		$this->dbcon=null;
	}
}