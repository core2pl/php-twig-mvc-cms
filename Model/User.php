<?php
namespace Model;

use Model\Base;

class User extends Base {
	
	private $name;
	private $dbcon;
	private $server = MYSQL_SERVER;
	private $database = MYSQL_DATABASE;
	private $prefix = MYSQL_PREFIX;
	
	public function __construct($name) {
		$this->name = $name;
		try {
			$this->dbcon = new \PDO("mysql:host=$server;dbname=$database", MYSQL_LOGIN, MYSQL_PASSWORD);
			$this->dbcon->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		} catch(\PDOException $e) {
			echo "Błąd: " . $e->getMessage();
		}
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getUserName($userId) {
		try {
			$query = $this->dbcon->prepare("SELECT id,nick FROM ".$this->prefix."_users WHERE id = :id");
			$query->bindValue(":id",$userId);
			$query->execute();
			$fetch=$query->fetchAll(\PDO::FETCH_ASSOC);
			if(!empty($fetch)) {
				return $fetch['nick'];
			} else {
				return false;
			}
		} catch(\PDOException $e) {
			echo "Błąd: " . $e->getMessage();
		}
	}
}