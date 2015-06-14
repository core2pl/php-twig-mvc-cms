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
		return $this->getRank();
	}
	
	public function setRank($rank) {
		$this->rank = $rank;
	}
}