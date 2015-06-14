<?php
namespace Service;

class Widgets {
	
	private $widgets_top;
	private $widgets_left;
	private $widgets_right;
	private $widgets_footer;

	public function __construct() {
		$json = new \Service\Json();
		$json->open("config.json");
		$this->widgets_top = $json->get("widgets_top");
		$this->widgets_left = $json->get("widgets_left");
		$this->widgets_right = $json->get("widgets_right");
		$this->widgets_footer = $json->get("widgets_footer");
	}
	
	public function makeWidgets($position,$rank) {
		switch ($position) {
			case "top":
				if(empty($this->widgets_top)) return array();
				$widgets_top = array();
				foreach ($this->widgets_top as $widget) {
					if($widget->getRank()>=$rank) {
						switch ($widget->type) {
							case "menu":
								$widgets_top[] = $widget->renderWidget($rank);
							break;
							case "clock":
								$clock = $widget->renderWidget($rank);
								$time = new \DateTime();
								$clock->time = $time->format($clock->format);
								$widgets_top[] = $clock;
							break;
						}
					}
				}
				return $widgets_top;
			break;
			case "left":
				if(empty($this->widgets_left)) return array();
				$widgets_left = array();
				foreach ($this->widgets_left as $widget) {
					if($widget->getRank()>=$rank) {
						switch ($widget->type) {
							case "menu":
								$widgets_left[] = $widget->renderWidget($rank);
							break;
							case "clock":
								$clock = $widget->renderWidget($rank);
								$time = new \DateTime();
								$clock->time = $time->format($clock->format);
								$widgets_left[] = $clock;
							break;
						}
					}
				}
				return $widgets_left;
			break;
			case "right":
				if(empty($this->widgets_right)) return array();
				$widgets_right = array();
				foreach ($this->widgets_right as $widget) {
					if($widget->getRank()>=$rank) {
						switch ($widget->type) {
							case "menu":
								$widgets_right[] = $widget->renderWidget($rank);
							break;
							case "clock":
								$clock = $widget->renderWidget($rank);
								$time = new \DateTime();
								$clock->time = $time->format($clock->format);
								$widgets_right[] = $clock;
							break;
						}
					}
				}
				return $widgets_right;
			break;
			case "footer":
				if(empty($this->widgets_footer)) return array();
				$widgets_footer = array();
				foreach ($this->widgets_footer as $widget) {
					if($widget->getRank()>=$rank) {
						switch ($widget->type) {
							case "menu":
								$widgets_footer[] = $widget->renderWidget($rank);
							break;
							case "clock":
								$clock = $widget->renderWidget($rank);
								$time = new \DateTime();
								$clock->time = $time->format($clock->format);
								$widgets_footer[] = $clock;
							break;
						}
					}
				}
				return $widgets_footer;
			break;
		}
	}
}