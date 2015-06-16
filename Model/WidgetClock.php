<?php
namespace Model;

use Model\Widget as Widget;

class WidgetClock extends Widget {

	private $color,$style;
	
	public function __construct($rank,$color="black",$style="") {
		parent::__construct($rank);
		$this->setType("clock");
		$this->color = $color;
		$this->style = $style;
	}

	public function getFormat() {
		return $this->format;
	}	
	
	public function setFormat($format) {
		$this->format = $format;
	}
	
	public function getColor() {
		return $this->color;
	}
	
	public function setColor($color) {
		$this->color = $color;
	}
	
	public function getStyle() {
		return $this->style;
	}
	
	public function setStyle($style) {
		$this->style = $style;
	}
}