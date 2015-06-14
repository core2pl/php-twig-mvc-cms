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
				$post = $fetch[0];
				$posts = new Post($post['id'],$post['title'],$post['text'],$post['date'],$post['author']);
				$posts->comments = $this->getComments($post['id']);
				$posts->setAuthor($this->getUserName($posts->getAuthorId()));
				return $posts;
			} else {
				return false;
			}
		} catch(\PDOException $e) {
			echo "Błąd: " . $e->getMessage();
		}
	}
	
	
	public function addPost(\Model\Post $post) {
		try {
			$query = $this->dbcon->prepare("INSERT INTO ".$this->prefix."_news (id, title, text, type, date, author) VALUES (NULL, :title, :text, \"post\", NOW(), :author);");
			$query->bindValue(":title",$post->getTitle());
			$query->bindValue(":text",$post->getText());
			$query->bindValue(":author",$post->getAuthorId());
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
	
	public function editPost(\Model\Post $post) {
		try {
			$query = $this->dbcon->prepare("UPDATE ".$this->prefix."_news SET title = :newtitle,text = :newtext, date = Now() WHERE id = :id");
			$query->bindValue(":newtitle",$post->getTitle());
			$query->bindValue(":newtext",$post->getText());
			$query->bindValue(":id",$post->getId());
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
			echo "Błąd: " . $e->getMessage();
		}
	}
	
	public function getComments($postId) {
		try {
			$query = $this->dbcon->prepare("SELECT ".$this->prefix."_comments.id, ".$this->prefix."_comments.text, ".$this->prefix."_comments.author, ".$this->prefix."_comments.post, ".$this->prefix."_comments.date, ".$this->prefix."_users.nick FROM ".$this->prefix."_comments INNER JOIN ".$this->prefix."_users ON ".$this->prefix."_comments.author=".$this->prefix."_users.id WHERE ".$this->prefix."_comments.post=".$postId." ORDER BY ".$this->prefix."_comments.date DESC;");
			$query->execute();
			$fetch=$query->fetchAll(\PDO::FETCH_ASSOC);
			if(!empty($fetch)) {
				$comments = array();
				foreach($fetch as $comment) {
					$comments[] = new \Model\Comment($comment['id'], $comment['text'], $comment['author'], $comment['date'], $comment['post'], $comment['nick']);
				}
				return $comments;
			} else {
				return false;
			}
		} catch(\PDOException $e) {
			echo "Błąd: " . $e->getMessage();
		}
	}
	
	public function addComment(\Model\Comment $comment) {
		try {
			$query = $this->dbcon->prepare("INSERT INTO ".$this->prefix."_comments (id, text, author, post, date) VALUES (NULL, :text, :author, :post, NOW());");
			$query->bindValue(":text",$comment->getText());
			$query->bindValue(":post",$comment->getPostId());
			$query->bindValue(":author",$comment->getAuthor());
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
	
	public function removeComment($id) {
		try {
			$query = $this->dbcon->prepare("DELETE FROM ".$this->prefix."_comments WHERE id = :id");
			$query->bindValue(":id",$id);
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
	
	public function getUserData($userId) {
		try {
			$query = $this->dbcon->prepare("SELECT * FROM ".$this->prefix."_users WHERE id = :id");
			$query->bindValue(":id",$userId);
			$query->execute();
			$fetch=$query->fetch();
			if(!empty($fetch)) {
				return new \Model\User($fetch['id'], $fetch['nick'], $fetch['email'], $fetch['lvl'], $fetch['last_login'], $fetch['rank']);
			} else {
				return false;
			}
		} catch(\PDOException $e) {
			echo "Błąd: " . $e->getMessage();
		}
	}
	
	public function removeUser($userId) {
		try {
			if($this->userExists($userId)) {
				$query = $this->dbcon->prepare("DELETE FROM ".$this->prefix."_users WHERE id = :id");
				$query->bindValue(":id",$userId);
				$query->execute();
				$fetch=$query->rowCount();
				if($fetch==1) {
					$query = $this->dbcon->prepare("DELETE FROM ".$this->prefix."_comments WHERE author = :id");
					$query->bindValue(":id",$userId);
					$query->execute();
					$fetch=$query->rowCount();
					$query = $this->dbcon->prepare("DELETE FROM ".$this->prefix."_news WHERE author = :id");
					$query->bindValue(":id",$userId);
					$query->execute();
					$fetch=$query->rowCount();
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} catch(\PDOException $e) {
			echo "Błąd: " . $e->getMessage();
		}
	}
	
	public function banUser($userId) {
		try {
			if($this->userExists($userId)) {
				$query = $this->dbcon->prepare("UPDATE ".$this->prefix."_users SET rank = 4 WHERE id = :id");
				$query->bindValue(":id",$userId);
				$query->execute();
				$fetch=$query->rowCount();
				return true;
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
			echo "Błąd: " . $e->getMessage();
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
			echo "Błąd: " . $e->getMessage();
		}
	}
	
	public function setLastLogin($userId) {
		try {
			$query = $this->dbcon->prepare("UPDATE ".$this->prefix."_users SET last_login = NOW() WHERE id = :id");
			$query->bindValue(":id",$userId);
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
			echo "Błąd: " . $e->getMessage();
		}
	}
	
	function listUsers() {
		try {
			$query = $this->dbcon->prepare("SELECT id,nick,email,lvl,rank,last_login FROM $this->prefix"."_users");
			$query->execute();
			$fetch=$query->fetchAll(\PDO::FETCH_ASSOC);
			if(!empty($fetch)) {
				$return = array();
				foreach ($fetch as $user) {
					$return[] = new \Model\User($user['id'], $user['nick'], $user['email'], $user['lvl'], $user['last_login'], $user['rank']);
				}
				return $return;
			} else {
				return false;
			}
		} catch(\PDOException $e) {
			$page = "Błąd: " . $e->getMessage();
		}
	}
	
	function change_data(\Model\User $user) {
		try {
			$query = $this->dbcon->prepare("UPDATE $this->prefix"."_users SET nick = :newnick, email = :newemail, rank=:newrank, lvl=:newlvl WHERE id = :id");
			$query->bindValue(":newnick",$user->getNick());
			$query->bindValue(":newemail",$user->getEmail());
			$query->bindValue(":newrank",$user->getRank());
			$query->bindValue(":newlvl",$user->getLvl());
			$query->bindValue(":id",$user->getId());
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
	
	public function changeUserPassword($id,$pass) {
		try {
			$salt = uniqid(mt_rand(), true);
			$password = hash('sha256', $salt . $pass);
			$query = $this->dbcon->prepare("UPDATE $this->prefix"."_users SET password = :pass, salt = :salt WHERE id = :id");
			$query -> bindValue(":pass", $password);
			$query -> bindValue(":salt", $salt);
			$query->bindValue(":id",$id);
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
	
	public function loginUser($nick,$pass) {
		try {
			$query = $this->dbcon->prepare("SELECT id,nick,password,salt FROM ".$this->prefix."_users WHERE nick = :nick");
			$query->bindValue(":nick",$nick);
			$query->execute();
			$fetch=$query->fetch();
			if(!empty($fetch)) {
				$password = $fetch['password'];
				$salt = $fetch['salt'];
				$password2 = hash('sha256', $salt .$pass);
				if($password == $password2) {
					$_SESSION['id']=$fetch['id'];
					return 0;
				} else {
					return 1;
				}
			} else {
				return 2;
			}
		} catch(\PDOException $e) {
			echo "Błąd: " . $e->getMessage();
		}
	}
	
	public function registerUser($nick,$pass,$email) {
		try {
			$query = $this->dbcon->prepare("SELECT id,nick FROM ".$this->prefix."_users WHERE nick = :nick");
			$query->bindValue(':nick', $nick);
			$query-> execute();
			$fetch = $query->fetch();
			if(empty($fetch)) {
				$salt = uniqid(mt_rand(), true);
				$password = hash('sha256', $salt . $pass);
				$query = $this->dbcon->prepare("INSERT INTO ".$this->prefix."_users VALUES ('',?,?,?,?,NOW(),?,'1')");
				$return = $query->execute(array($nick, $email, $password, $salt, 2));
				$this->loginUser($nick, $pass);
				return true;
			} else {
				return false;
			}
		} catch(\PDOException $e) {
			echo "Błąd: " . $e->getMessage();
		}
	}
	
	public function closeConnection() {
		$this->dbcon=null;
	}
}