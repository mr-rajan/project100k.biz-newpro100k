<?php
defined('BASEPATH') OR exit('No direct script access allowed.');

class User_model extends CI_Model {   
		  
		  public function __construct(){
		  		parent::__construct();
		  }
		  
		 //function to authenticate user
		 public function authUser($param){
		 		if(is_array($param)){
					 
				}
				else{
					return false;
				}
		 }
}
?>