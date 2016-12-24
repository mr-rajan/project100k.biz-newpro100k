<?php
defined('BASEPATH') OR exit('No direct script access allowed.');

class Promotion_model extends CI_Model {   
		  
		  public function __construct(){
		  		parent::__construct();
		  }
		  
		// function to get lists of all users
		public function get_fieldDetails_by_join(){
			 $this->db->select('bmf.*');
			 $this->db->from('tbl_promotion_masterfields bmf');
			 $this->db->join('tbl_promotion_field_options bfo','bfo.country = c.id','left');
			 $this->db->join('tbl_users refU','u.refID = refU.id','left');			 
			 $this->db->order_by('id','desc');
			 $query = $this->db->get();
			 $result = $query->result();
			 return $result;
		}
		
		// function to get lists of all users
		public function get_banner_field_value_Details($catgId){
			
			// $this->db->join('tbl_banner_field_values mfv','mfv.masterfieldsID = mfo.id','inner');
			 $fields	=	$this->get_bannerurl_head($catgId);
			 print'<pre/>'; print_r($fields);die;
			 foreach($result as $key=>$val){
				 
			 }
			 return $result;
		}
		
		public function get_promotion_fieldsByCatgId($catgId,$whereCondition=NULL){
		     $this->db->select('mct.*, mfo.*');
			 $this->db->from('tbl_promotion_mastercategory mct');
			 $this->db->join('tbl_promotion_masterfields mfo','mfo.promotionCategoryId = mct.id','inner');
			 $this->db->where('mct.id',$catgId);
			 $this->db->where('mfo.status','Active');
			 if($whereCondition!=NULL){
				  if(is_array($whereCondition)){
						foreach($whereCondition as $key=>$where){
							$this->db->where($key,$where);	
						}
				  }
			 }
			 $this->db->order_by('mfo.order','asc');
			 
			 $query = $this->db->get();			 
			 $result = $query->result_array();			
			 return $result;	
		}
		
		//public function to filter the rows heading for banner
		public function filter_promotion_rowhead($catgId){
			 $fields	=	$this->get_promotion_fieldsByCatgId($catgId);			 
			 $rowheadings = array();
			 if(!empty($fields)){
					foreach($fields as $key=>$field) {
						 if($field['addtoTableHeading'] == 'yes')	{
								$temp	=	array('id'=>$field['id'], 'headingTitle'=>ucwords($field['title']),'sortable'=>$field['sortable']); 
								array_push($rowheadings,$temp);
						 }						 
					}
			 }
			
			 return $rowheadings;
		}
		
		//function to query rrecods for fields options
		public function field_options($fieldId){
			$this->db->select('tbfo.*');	
			$this->db->from('tbl_promotion_field_options tbfo');
			$this->db->where('tbfo.masterfieldsID',$fieldId);
			$this->db->order_by('tbfo.precedence','asc');
			$query	=	$this->db->get();
			$result	=	$query->result_array();
			return $result;
		}
}
?>