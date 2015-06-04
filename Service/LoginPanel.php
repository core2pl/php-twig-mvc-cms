<?php
namespace Service;

class LoginPanel {
	
	public function getData($userName) {
		if(isset($_SESSION['id'])) {
			$return = (object) null;
			$return->logged = $this->isLogged();
			$return->user_panel = "/user/show/".$_SESSION['id'];
			$return->user = $userName;
			$return->logout = "/logout";
		} else {
			$return = (object) null;
			$return->logged = $this->isLogged();
			$return->login = "/login";
			$return->register = "/register";
			$return->user = "";
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
