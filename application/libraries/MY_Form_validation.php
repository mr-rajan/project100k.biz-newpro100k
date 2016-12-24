<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class MY_Form_validation extends CI_Form_validation {
 
	protected $CI;
 
	function __construct()
	{
		parent::__construct();
 
		$this->CI =& get_instance();
	}
	/**
 * PCI compliance password
 *
 * @access  public
 * @param   $str
 * @return  bool
 */
	public function pci_password($str)
	{
		$special = '!@#$%*-_=+.';
		
		$this->CI->form_validation->set_message('password', '%s must be between 8 and 15 characters in length, must not contain two consecutively repeating characters, contain at least one upper-case letter, at least one lower-case letter, at least one number, and at least one special character ('.$special.')');
		
		return (preg_match('/^(?=^.{8,15}$)(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])(?=.*['.$special.'])(?!.*?(.)\1{1,})^.*$/', $str)) ? TRUE : FALSE;
	}	
}