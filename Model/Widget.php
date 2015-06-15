<?php
namespace Model;

class Widget {

	private $rank,$type;
	
	public function __construct($rank) {
		$this->rank = $rank;
	}
	
	public function renderWidget($rank) {
		return $this;
	}
	
	public function getRank() {
		return $this->rank;
	}
	
	public function setRank($rank) {
		$this->rank = $rank;
	}
	
	public function getType() {
		return $this->type;
	}
	
	public function setType($type) {
		$this->type = $type;
	}
}