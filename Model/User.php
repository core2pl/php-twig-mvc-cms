<?php
namespace Model;

use \Model\Base as Base;

class User extends Base {
	
	private $id,$nick,$email;
	
	public function __construct($id,$nick,$email) {
		$this->id = $id;
		$this->nick = $nick;
		$this->email = $email;
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
}