<?php
namespace Model;

use \Model\Base as Base;

class User extends Base {
	
	private $id,$nick,$email,$lvl,$last,$rank,$online;
	
	public function __construct($id,$nick,$email,$lvl,$lastLogin,$rank) {
		$this->id = $id;
		$this->nick = $nick;
		$this->email = $email;
		$this->lvl = $lvl;
		$this->last = $lastLogin;
		$this->rank = $rank;
		$now = new DateTime();
		$date = new DateTime($user->getLastLogin());
		$diff = $date->diff($now);
		if($diff->format("%y%m%d%h%i%s")>30) {
			$this->online = false;
		} else {
			$this->online = true;
		}
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getNick() {
		return $this->nick;
	}
	
	public function setNick($nick) {
		$this->nick = $nick;
	}
	
	public function getEmail() {
		return $this->email;
	}
	
	public function setEmail($email) {
		$this->email = $email;
	}
	
	public function getLvl() {
		return $this->lvl;
	}
	
	public function setLvl($lvl) {
		$this->lvl = $lvl;
	}
	
	public function getLastLogin() {
		return $this->last;
	}
	
	public function setLastLogin($lastLogin) {
		$this->last = $lastLogin;
	}
	
	public function getRank() {
		return $this->rank;
	}
	
	public function setRank($rank) {
		$this->rank = $rank;
	}
}