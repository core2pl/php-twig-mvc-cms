<?php
class SPLAutoLoad {

	public function AutoLoader($className) {
		$file = str_replace("\\","/",$className);
		if(file_exists($file.".php")) {
			require $file.'.php';
		}
	}
	
	public function main() {
		spl_autoload_register(array($this,'AutoLoader'));
	}
}