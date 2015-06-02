<?php
namespace Service;

class Table {

	public $rows,$js;
	
	public function __construct() {
		$rows = array();
		
	}
	
	public function setJs($js) {
		$this->js = $js;
	}
	
	public function addRow($value1,$value2) {
		$this->rows[] = array("value1" => $value1, "value2" => $value2);
	}
	
}