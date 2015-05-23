<?php
namespace Model;

use \Model\Base;

class Form extends \Model\Base {

	private $inputs,$action,$method,$style,$class;
	
	public function __construct($action,$method,$class="",$style="") {
		$this->action = $action;
		$this->method = $method;
		$this->style = $style;
		$this->class = $class;
		$this->inputs = array();
	}
	
	public function addInput($type,$name,$title,$value="",$class="",$titleClass="",$style="",$titleStyle="") {
		$input = (object) null;
		$input->$type = $type;
		$input->$name = $name;
		$input->$value = $value;
		$input->$class = $class;
		$input->$title = $title;
		$input->$titleClass = $titleClass;
		$input->$style = $style;
		$input->$titleStyle = $titleStyle;
		$this->inputs[] = $input;
	}
}