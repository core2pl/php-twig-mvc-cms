<?php
namespace Model;

abstract class Base {
	
	abstract function __construct($name);
	
	abstract function getName();
}