<?php
namespace Service;


class Form {

	public $inputs,$action,$method,$style,$class;
	
	public function __construct($action,$method,$class="",$style="") {
		$this->action = $action;
		$this->method = $method;
		$this->style = $style;
		$this->class = $class;
		$this->inputs = array();
	}
	
	public function addInput($html,$type,$name,$title,$value="",$class="",$titleClass="",$style="",$titleStyle="") {
		$input = (object) null;
		$input->html = $html;
		$input->type = $type;
		$input->name = $name;
		$input->value = $value;
		$input->class = $class;
		$input->title = $title;
		$input->titleClass = $titleClass;
		$input->style = $style;
		$input->titleStyle = $titleStyle;
		$this->inputs[] = $input;
		return $this;
	}
}