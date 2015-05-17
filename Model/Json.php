<?php
namespace Model;

use Model\Base;

class Json extends Base {
	
	private $name;
	private $json;
	private $fname;
	
	public function open($fname) {
		$this->fname = $fname;
		$this->json = unserialize(json_decode(file_get_contents($fname),true));
	}
	
	public function save() {
		file_put_contents($this->fname, json_encode(serialize($this->json)));
	}
	
	public function put($name,$value) {
		$this->json->$name = $value;
	}
	
	public function get($name) {
		return $this->json->$name;
	}
}