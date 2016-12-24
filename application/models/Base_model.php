<?php
defined('BASEPATH') OR exit('No direct script access allowed.');

class Base_model extends CI_Model { 
  
		  private $tableToExecute = '';
		  private $whereClause = '';
		  private $tableTouples = '';
		  public function __construct(){
		  		parent::__construct();			
		  }
		  
		  //Common function to check any record exists or not
		  public function is_Record_Exists($table=NULL, $fields=NULL, $where=NULL,  $orderby_field=NULL, $orderby_precedence=NULL, $limit_start=NULL, $limit_to=NULL){
		  		
				($table!=NULL) 	? $this->tableToExecute = 	$table 				: 	$this->tableToExecute 		= 	'tbl_users';
				($fields!=NULL) ? $this->tableTouples 	= 	implode(',',$fields): 	$this->tableTouples 		= 	'*';
				($where!=NULL) 	? $this->whereClause 	= 	$where 				: 	$this->whereClause 			= 	' 1=1 ';
				
				
				$this->db->select($this->tableTouples)
				         ->from($this->tableToExecute)
						 ->where($this->whereClause);
				if(($orderby_field!=NULL ||$orderby_field!='') && ($orderby_precedence!=NULL ||$orderby_precedence!=''))
				$this->db->order_by($orderby_field, $orderby_precedence);	
				if(($orderby_field!=NULL ||$orderby_field!='') && ($orderby_precedence!=NULL ||$orderby_precedence!=''))
				$this->db->limit($limit_start,$limit_to);	 
				$query	=	$this->db->get();				
				$resultSet = $query->row();
				return $resultSet;
		  }
		  
		 
		  //function to count total number of records in a table
		  public function count_records($table,$where=NULL){
				 $this->db->select('id')
				      ->from($table);
				 if($where!=NULL || $where!='')
				 $this->db->where($where);
				 else
				 $this->db->where('1=1');
				 	  
				 $query = $this->db->get();
				 $result	= $query->num_rows();
				 return $result;	  
		  }
		  
		   //Common function to get any set of records with where in condition
		  public function get_RecordsWithIn($recordIds,$fieldId){
			
				$this->db->select('optionvalue')
				         ->from('tbl_bannerurl_field_options')
						 ->where_in('id',$recordIds)
						 ->where('masterfieldsID',$fieldId);
				$query	=	$this->db->get();				
				$resultSet = $query->result();
				$concatinatedOptions	=	'';
				if(!empty($resultSet)){
					foreach($resultSet as $key=>$val){
					    $concatinatedOptions	.= $val->optionvalue.', ';		
					}
					$concatinatedOptions	=	substr($concatinatedOptions,0,-2);
				}
				return $concatinatedOptions;
		  }
		  
		   //Common function to list of records 
		  public function get_All_Records($table=NULL, $fields=NULL, $where=NULL, $orderby_field=NULL, $orderby_precedence=NULL){
		  		
				($table!=NULL) 	? $this->tableToExecute = 	$table 				: 	$this->tableToExecute 		= 	'tbl_users';
				($where!=NULL) 	? $this->whereClause 	= 	$where 				: 	$this->whereClause 			= 	' 1=1 ';
				($fields!=NULL) ? $this->tableTouples 	= 	implode(',',$fields): 	$this->tableTouples 		= 	'*';
				
				$this->db->select($this->tableTouples)
				         ->from($this->tableToExecute)
						 ->where($this->whereClause);
				if($orderby_field!=NULL && $orderby_precedence!=NULL)
				$this->db->order_by($orderby_field,$orderby_precedence);		 
				$query	=	$this->db->get();	
				$resultSet = $query->result();
				return $resultSet;
		 }
		 // function to create random strong password
		public function generateStrongPassword($available_sets = 'luds', $length = 9, $add_dashes = false )
		{
			$sets = array();
			if(strpos($available_sets, 'l') !== false)
				$sets[] = 'abcdefghjkmnpqrstuvwxyz';
			if(strpos($available_sets, 'u') !== false)
				$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
			if(strpos($available_sets, 'd') !== false)
				$sets[] = '23456789';
			if(strpos($available_sets, 's') !== false)
				$sets[] = '!@#$%&*?';
			$all = '';
			$password = '';
			foreach($sets as $set)
			{
				$password .= $set[array_rand(str_split($set))];
				$all .= $set;
			}
			$all = str_split($all);
			for($i = 0; $i < $length - count($sets); $i++)
				$password .= $all[array_rand($all)];
			$password = str_shuffle($password);
			if(!$add_dashes)
				return $password;
			$dash_len = floor(sqrt($length));
			$dash_str = '';
			while(strlen($password) > $dash_len)
			{
				$dash_str .= substr($password, 0, $dash_len) . '-';
				$password = substr($password, $dash_len);
			}
			$dash_str .= $password;
			return $dash_str;
		}
		 //function to save record to table
		 public function saveRecord($data, $table=NULL){
		 	
			($table!=NULL) 	? $this->tableToExecute = 	$table 				: 	$this->tableToExecute 		= 	'tbl_users';
		 	$this->db->insert($this->tableToExecute,$data);
			return ($this->db->affected_rows()>0) ? true : false;
		 }
		 
		 //function to update record to table
		 public function updateRecord($data, $table=NULL, $where=NULL){
		 	
			($table!=NULL) 	? $this->tableToExecute = 	$table 				: 	$this->tableToExecute 		= 	'tbl_users';
		 	$this->db->update($this->tableToExecute,$data,$where);
			return ($this->db->affected_rows()>0) ? true : false;
		 }
		 
		  
		 //function to delete record to table
		 public function deleteRecord($table=NULL, $where=NULL){
		 	
			($table!=NULL) 	? $this->tableToExecute = 	$table 				: 	$this->tableToExecute 		= 	'tbl_users';
		 	$this->db->delete($this->tableToExecute,$where);
			return ($this->db->affected_rows()>0) ? true : false;
		 }
		 
		 //function to delete record to table with using In
		 public function deleteRecordWithIn($records,$table=NULL){
		 	
			($table!=NULL) 	? $this->tableToExecute = 	$table 				: 	$this->tableToExecute 		= 	'tbl_users';
		 	$this->db->where_in('id',$records);
			$this->db->delete($this->tableToExecute);
			return ($this->db->affected_rows()>0) ? true : false;
		 }
		 
		 //function to update reocrd by where in clause
		 public function updateRecords($data, $table, $field){		 	
			$this->tableToExecute = 	$table;		 	
			$this->db->update_batch($this->tableToExecute,$data,'id');
			return ($this->db->affected_rows()>0) ? true : false;
		 }
		  
		 	// function to get lists of all users
		public function get_all_downline($userId){
			 $this->db->select('u.id, u.email, u.gender, u.status, u.contactno, u.registeredOn, CONCAT(u.first_name," ",u.last_name) name, c.country_name');
			 $this->db->from('tbl_users u');
			 $this->db->join('tbl_countries c','u.country = c.id','left');
			 $this->db->where('refID',$userId);
			 $this->db->order_by('id','desc');
			 $query = $this->db->get();
			 $result = $query->result();
			 return $result;
		} 
		  
}
?>