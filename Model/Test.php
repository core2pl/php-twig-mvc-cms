<?php
namespace Model;

require_once 'Base.php';

use Model\BaseModel;

class TestModel extends BaseModel {
	
	private $test_message;
	
	public function __construct() {
		$this->test_mesage = (string)null;
	}
	
	public function read() {
		$this->test_message = "witaj!";
	}
	
	public function get() {
		return $this->test_message;
	}
}