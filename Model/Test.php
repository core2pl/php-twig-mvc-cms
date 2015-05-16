<?php
namespace Model;

use Model\Base;

class Test extends Base {
	
	private $test_message;
	private $name;
	
	public function __construct($name) {
		$this->test_mesage = (string)null;
		$this->name = $name;
	}
	
	public function Read() {
		$this->test_message = "witaj!";
	}
	
	public function getData() {
		return $this->test_message;
	}
	
	public function getName() {
		return $this->name;
	}
}