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
			$this->dbcon = new \PDO("mysql:host=$this->server;dbname=$this->database", MYSQL_LOGIN, MYSQL_PASSWORD);
			$this->dbcon->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		} catch(\PDOException $e) {
			echo "Błąd: " . $e->getMessage();
		}
	}
	
	
}