<?php
namespace Model;

use \Model\Widget as Widget;

class WidgetUsers extends Widget {

	private $linked,$option;
	
	public function __construct($rank) {
		parent::__construct($rank);
		$this->linked = 1;
		$this->option = 0;
		$this->setType("users");
	}
	
	public function linkUsers($vars) {
		$this->linked = $vars;
	}
	
	public function linkedUsers() {
		return $this->linked;
	}
	
	public function setOption($option) {
		if($option == 0 || $option == 1 || $option == 2) {
			$this->option = $option;
			return true;
		} else {
			return false;
		}
	}
	
	public function getOption() {
		return $this->option;
	}
}