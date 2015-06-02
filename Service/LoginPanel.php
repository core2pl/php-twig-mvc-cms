<?php
namespace Service;

class LoginPanel {
	
	public function getData($userName) {
		if(isset($_SESSION['id'])) {
			$return = (object) null;
			$return->logged = $this->isLogged();
			$return->user_panel = "/user/panel";
			$return->user = $userName;
			$return->logout = "/logout";
		} else {
			$return = (object) null;
			$return->logged = $this->isLogged();
			$return->register = "/register";
			$return->login = "/login";
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
