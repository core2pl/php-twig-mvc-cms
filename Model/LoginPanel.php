<?php
namespace Model;

use Model\Base;

class LoginPanel extends Base {
	
	public function getData($userName) {
		if(isset($_SESSION['id'])) {
			$return = (object) null;
			$return->logged = $this->isLogged();
			$return->user_panel = "?page=user&action=panel";
			$return->user = $userName;
			$return->logout = "?action=logout";
		} else {
			$return = (object) null;
			$return->logged = $this->isLogged();
			$return->register = "?action=register";
			$return->login = "?action=login";
		}
		return $return;
	}
	
	public function isLogged() {
		if(isset($_SESSION['id'])) {
			return true;
		} else {
			return false;
		}
	}
	
}
