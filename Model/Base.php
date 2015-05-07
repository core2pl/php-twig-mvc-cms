<?php
namespace Model;

abstract class Base {
	
	abstract function __construct($name);
	
	abstract function read();
	
	abstract function getName();
	
	abstract function getData();
}