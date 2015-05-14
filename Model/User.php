<?php
namespace Model;

use Model\Base;

class User extends Base {
	
	private $name;
	
	public function __construct($name) {
		$this->name = $name;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getUserName($userId) {
		try {
			$query = $this->dbcon->prepare("SELECT id,nick FROM $prefix"."_users WHERE id = :id");
			$query->bindValue(":id",$userId);
			$query->execute();
			$fetch=$query->fetchAll(PDO::FETCH_ASSOC);
			if(!empty($fetch)) {
				return $fetch['nick'];
			} else {
				return false;
			}
		} catch(PDOException $e) {
			return "B³¹d: " . $e->getMessage();
		}
	}
}