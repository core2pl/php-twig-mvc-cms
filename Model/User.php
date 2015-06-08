<?php
namespace Model;

use \Model\Base as Base;

class User extends Base {
	
	private $id,$nick,$email,$lvl,$last,$rank;
	
	public function __construct($id,$nick,$email,$lvl,$lastLogin,$rank) {
		$this->id = $id;
		$this->nick = $nick;
		$this->email = $email;
		$this->lvl = $lvl;
		$this->last = $lastLogin;
		$this->rank = $rank;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	
	public function getNick() {
		return $this->nick;
	}
	
	public function setNick($nick) {
		$this->nick = $nick;
		return $this;
	}
	
	public function getEmail() {
		return $this->email;
	}
	
	public function setEmail($email) {
		$this->email = $email;
		return $this;
	}
	
	public function getLvl() {
		return $this->lvl;
	}
	
	public function setLvl($lvl) {
		$this->lvl = $lvl;
		return $this;
	}
	
	public function getLastLogin() {
		return $this->last;
	}
	
	public function setLastLogin($lastLogin) {
		$this->last = $lastLogin;
		return $this;
	}
	
	public function getRank() {
		return $this->rank;
	}
	
	public function getRankName() {
		switch ($this->rank) {
			case 1:
				return "Admin";
			break;
			case 2:
				return "Użytkownik";
			break;
			case 3:
				return "Gość";
			break;
			case 4:
				return "Zbanowany";
			break;
		}
		
	}
	
	public function setRank($rank) {
		$this->rank = $rank;
		return $this;
	}
	
	public function getStatus() {
		$now = new \DateTime();
		$date = new \DateTime($this->getLastLogin());
		$diff = $date->diff($now);
		if($diff->format("%Y%M%D%H%I%S")>30) {
			return "Offline";
		} else {
			return "Online";
		}
	}
}