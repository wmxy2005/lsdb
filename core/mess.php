<?php
class Mess {
	public $mess = array();
	
	public function __construct($lang){
		$messFile = 'mess.' . $lang . ".php";
		require_once $messFile;
		$this->mess = getMess();
    }
}