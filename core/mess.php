<?php
class Mess {
	public $mess = array();
	
	public function __construct($lang){
		$messFile = 'mess.' . $lang . ".php";
		require_once $messFile;
		$mess = getMess();
		$messExtFile = 'mess.' . $lang . ".ext.php";
		if(file_exists(__DIR__ . '/' . $messExtFile)) {
			require_once $messExtFile;
			$this->mess = array_merge($mess, getMessExt());
		} else {
			$this->mess = $mess;
		}
    }
}