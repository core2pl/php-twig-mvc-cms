<?php
namespace view;

abstract class BaseView {
	
	abstract function add_model();
	
	abstract function render();
}