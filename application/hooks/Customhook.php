<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customhook{	
	private $ci;
	public function __construct(){
		$this->ci =	&get_instance();
	}
	public function callhook(){
		echo 'calling hook';
	}
}

?>