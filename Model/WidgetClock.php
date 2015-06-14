<?php
namespace Model;

use \Model\Widget;

class WidgetClock extends \Model\Widget {

	private $format;
	
	public function __construct($rank, $format="h:i:s") {
		$this->format = $format;
		$this->type = "clock";
	}

	public function getFormat() {
		return $this->format;
	}	
	
	public function setFormat($format) {
		$this->format = $format;
	}
}