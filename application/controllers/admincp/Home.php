<?php
defined('BASEPATH') OR exit('No direct script access allowed.');

class Home extends CI_Controller{
	
	public function __construct(){
	    parent::__construct();	
		$this->load->database();
        $this->load->model('base_model');
	}
	//function to show the admin login
	public function index(){		
		$data['title'] = 'Projects 100K';			
		$this->load->view('templates/admincp/loginheader', $data);
		$this->load->view('admincp/home/index', $data);
		$this->load->view('templates/admincp/loginfooter', $data);
	}
	//function to show the admin dashboard
	public function dashboard(){		
		$data['title'] = 'Projects 100K';			
		$this->load->view('templates/admincp/header', $data);
		$this->load->view('admincp/account', $data);
		$this->load->view('templates/admincp/footer', $data);
	}
	
	
}


?>