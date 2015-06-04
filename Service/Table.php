<?php
namespace Service;

class Table {

	public $rows,$js,$row=0;
	
	public function __construct() {
		$rows = array();
		
	}
	
	public function setJs($js) {
		$this->js = $js;
	}
	
	
	public function addCell($text,$color,$href) {
		$obj = (object)null;
		$obj->text = $text;
		$obj->color = $color;
		$obj->href = $href;
		$this->rows[$this->row][] = $obj;  
	}
	
	public function nextRow() {
		$this->row++;
	}
	
}