<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class CI_Customlib{
 
	protected $CI;
 
	function __construct()
	{
		$this->CI =& get_instance();
	}
	public function test(){
		echo 'hi';
	}
}