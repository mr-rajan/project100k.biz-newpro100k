<?php
defined('BASEPATH') OR exit('No direct script access allowed.');

class Downline_model extends CI_Model {   
		  
		  public function __construct(){
		  		parent::__construct();
		  }
		  
		// function to get lists of all users
		public function get_all_downline($userId){
			 $this->db->select('u.id, u.email, u.gender, u.status, u.contactno, u.registeredOn, CONCAT(u.first_name," ",u.last_name) name, c.country_name');
			 $this->db->from('tbl_users u');
			 $this->db->join('tbl_countries c','u.country = c.id','left');
			 $this->db->where('isDeleted','no');
			 $this->db->where('refID',$userId);
			 $this->db->order_by('id','desc');
			 $query = $this->db->get();
			 $result = $query->result();
			 return $result;
		}
}
?>