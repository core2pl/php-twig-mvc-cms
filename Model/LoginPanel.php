<?php
namespace Model;

use Model\Base;

class LoginPanel extends Base {
	
	public function getData($user) {
		if(isset($_SESSION['id'])) {
			$return = (object) null;
			$return->logged = $this->isLogged();
			$return->user_panel = "?page=user&action=panel";
			$return->user = $user;
			$return->logout = "?page=logout";
		} else {
			$return = (object) null;
			$return->logged = $this->isLogged();
			$return->register = "?page=register";
			$return->login = "?page=login";
		}
	}
	
	public function isLogged() {
		if(isset($_SESSION['id'])) {
			return true;
		} else {
			return false;
		}
	}

}
