<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('get_banner_categories_lists'))
{
    function get_banner_categories_lists()
    {
       $ci = & get_instance();
	   $ci->load->database();
	   $ci->load->model('base_model');
	   $data	=	$ci->base_model->get_All_Records('tbl_bannerurl_mastercategory',array('id','title'));	
	   $bannerCategoryUrl	=	'';	
	   if(!empty($data)){		  
			foreach($data as $key=>$val)	{	
					$active = '';
					$link	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$ci->encrypt->encode($val->id));	
					
					if(strpos($_SERVER['REQUEST_URI'],'bannercategory')){						
						if($ci->uri->segment(3)=='addnew'){
							$decrypted_subCategoryId	=	$ci->encrypt->decode(str_replace(array('-', '_', '~'), array('+', '/', '='),$ci->uri->segment(4)));	
						}
						elseif($ci->uri->segment(3)=='edit'){
							$catgTitle					=	strtolower(str_replace(array(' ','-'),array('_'),$ci->security->xss_clean($ci->uri->segment(4))));
							$categoryRecordInfo			=	$ci->base_model->is_Record_Exists('tbl_bannerurl_mastercategory',array('id'),array('uri_string'=>$catgTitle));
							$decrypted_subCategoryId	=	$categoryRecordInfo->id;
						}
						else{
							$decrypted_subCategoryId	=	$ci->encrypt->decode(str_replace(array('-', '_', '~'), array('+', '/', '='),$ci->uri->segment(3)));	
						}						
						if($decrypted_subCategoryId ===	$val->id)
						$active = 'class="active"';
						
					}
					$bannerCategoryUrl	.=	'<li '.$active.'>
											<a href="'.base_url().'admincp/bannercategory/'.$link.'">
											 <i class="fa fa-circle-o"></i>'.ucwords($val->title).'</a></li>';
					
			}
		}		
	   if($bannerCategoryUrl!='') echo $bannerCategoryUrl;
    }   
}

if ( ! function_exists('get_promotion_categories_lists'))
{
    function get_promotion_categories_lists()
    {
       $ci = & get_instance();
	   $ci->load->database();
	   $ci->load->model('base_model');
	   $data	=	$ci->base_model->get_All_Records('tbl_promotion_mastercategory',array('id','title'));	
	   $promotionCategory	=	'';	
	   if(!empty($data)){		  
			foreach($data as $key=>$val)	{	
					$active = '';
					$link	=	str_replace(array('+', '/', '='), array('-', '_', '~'),$ci->encrypt->encode($val->id));	
					
					if(strpos($_SERVER['REQUEST_URI'],'promotioncategory')){						
						if($ci->uri->segment(3)=='addnew'){
							$decrypted_subCategoryId	=	$ci->encrypt->decode(str_replace(array('-', '_', '~'), array('+', '/', '='),$ci->uri->segment(4)));	
						}
						elseif($ci->uri->segment(3)=='edit'){
							$catgTitle					=	strtolower(str_replace(array(' ','-'),array('_'),$ci->security->xss_clean($ci->uri->segment(4))));
							$categoryRecordInfo			=	$ci->base_model->is_Record_Exists('tbl_promotion_mastercategory',array('id'),array('uri_string'=>$catgTitle));
							$decrypted_subCategoryId	=	$categoryRecordInfo->id;
						}
						else{
							$decrypted_subCategoryId	=	$ci->encrypt->decode(str_replace(array('-', '_', '~'), array('+', '/', '='),$ci->uri->segment(3)));	
						}						
						if($decrypted_subCategoryId ===	$val->id)
						$active = 'class="active"';
						
					}
					$promotionCategory	.=	'<li '.$active.'>
											<a href="'.base_url().'admincp/promotioncategory/'.$link.'">
											 <i class="fa fa-circle-o"></i>'.ucwords($val->title).'</a></li>';
					
			}
		}		
	   if($promotionCategory!='') echo $promotionCategory;
    }   
}

if ( ! function_exists('clean'))
{
	function clean($string) {
	   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.	
	   return preg_replace('/[^A-Za-z0-9\-_]/', '', $string); // Removes special chars.
	}
}
