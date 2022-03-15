<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clientarea extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('usermodel');
		$this->load->library("session");
	}

    function index(){
		if($this->session->userdata('MEM_SESS')){
			redirect(base_url_lang()."/clientarea/profile");
		}
    	$post   = purify($this->input->post());
		$reload = true;
    	if($post){
    		$this->form_validation->set_rules('email', '"Email"', 'required'); 
    		$this->form_validation->set_rules('password', '"Password"', 'required'); 
			if ($this->form_validation->run() == FALSE){
				 $message = validation_errors();
				 $status  = 'error';
			} else {
				$filter['email'] = $post['email'];
				$filter['userpass'] = md5($post['password']);
		 		$check_user = $this->usermodel->findBy($filter, 1);
				if($check_user){
					$status  = 'success';
					$message = "Welcome back again ".$check_user['full_name'];

					$user_sess = array();
					$user_sess = array(
						'member_name'=>$check_user['full_name'],
						'member_id_auth_user_group'=>$check_user['id_auth_user_grup'],
						'id'=>$check_user['id_auth_user'],
						'member_id_auth_user'=>$check_user['id_auth_user'],
					);
					$this->session->set_userdata('MEM_SESS',$user_sess);
					$this->session->unset_userdata('ADM_SESS');
				} else {
					$status  = 'error';
					$message = "Sorry, email or password is wrong.";
				}
			}
	        $data['message'] = "$message";
	        $data['status']  = $status;
	        $data['reload']  = $reload;
	        echo json_encode($data);
    	} else{
    		$data['page_name'] = 'Client Area';
    		if($data['seo_title'] == ''){
    		    $data['seo_title'] = "MJM | Partner for Quality";
    		}
    		$data['meta_description'] = preg_replace('/<[^>]*>/', '', $data['meta_description']);
    		render('clientarea',$data); 

    	}
    }

	function register(){
		if($this->session->userdata('MEM_SESS')){
			redirect(base_url_lang()."/clientarea/profile");
		}
		$post   = purify($this->input->post());
		$reload = false;
    	if($post){
    		$this->form_validation->set_rules('full_name', '"Fullname"', 'required'); 
    		$this->form_validation->set_rules('email', '"Email"', 'required|valid_email'); 
    		$this->form_validation->set_rules('phone', '"Phone"', 'required'); 
    		$this->form_validation->set_rules('company_name', '"Company Name"', 'required'); 
    		$this->form_validation->set_rules('password', '"Password"', 'required'); 
    		$this->form_validation->set_rules('product_category[]', '"Product Category"', 'required'); 
    		$this->form_validation->set_rules('type_of_product', '"Type of Product"', 'required'); 
    		$this->form_validation->set_rules('message', '"Message"', 'required'); 
			if ($this->form_validation->run() == FALSE){
				 $message = validation_errors();
				 $status  = 'error';
			} else {				
				$filter['email'] = $post['email'];
				$check_email_exist = $this->usermodel->findBy($filter, 1);
				if($check_email_exist){
					$status  = 'error';
					$message = "Sorry, email already registered. Please login or use another email.";
				} else {
					$post['userpass'] = md5($post['password']);
					$post['id_auth_user_grup'] = 6;
					$post['userid'] = $post['email'];
					unset($post['retype_password'], $post['password']);
					$post['product_category'] = implode(',', $post['product_category']);
					$proses               = $this->usermodel->insert($post);
					if($proses){
						$status  = 'success';
						// $message = "Thanks, Your account will be proses 5 day work.";
						$message = "Register successfully, please login.";
						$reload = true;
					} else{
						$status  = 'error';
						$message = "Sorry, Please Try Again proses";
						$reload = true;
					}
				}
			}
	        $data['message'] = "$message";
	        $data['status']  = $status;
	        $data['reload']  = $reload;
	        echo json_encode($data);
    	} else{
			$data['page_name'] = 'Client Area';
			if($data['seo_title'] == ''){
				$data['seo_title'] = "MJM | Partner for Quality";
			}
			$data['meta_description'] = preg_replace('/<[^>]*>/', '', $data['meta_description']);
			render('register',$data); 
		}
	}

	function profile(){
		$data = $this->usermodel->findById(id_member());
		$data['page_name'] = 'Profile';
		if($data['seo_title'] == ''){
			$data['seo_title'] = "MJM | Partner for Quality";
		}
		$data['meta_description'] = preg_replace('/<[^>]*>/', '', $data['meta_description']);
		render('profile',$data); 
	}

	function update_profile(){
		$mem_sess = $this->session->userdata('MEM_SESS');
		if(!$mem_sess){
			redirect(base_url_lang()."/clientarea");
		}
		$post   = purify($this->input->post());
		$reload = true;
    	if($post){
    		$this->form_validation->set_rules('full_name', '"Fullname"', 'required'); 
    		$this->form_validation->set_rules('phone', '"Phone"', 'required'); 
    		$this->form_validation->set_rules('company_name', '"Company Name"', 'required'); 
			if ($this->form_validation->run() == FALSE){
				 $message = validation_errors();
				 $status  = 'error';
			} else {				
				$update['full_name'] = $post['full_name'];
				$update['phone'] = $post['phone'];
				$update['company_name'] = $post['company_name'];
				$proses               = $this->usermodel->update($update, id_member());
				if($proses){
					$status  = 'success';
					$message = "Updated data successfully.";
				} else{
					$status  = 'error';
					$message = "Sorry, Please Try Again proses";
				}
			}
	        $data['message'] = "$message";
	        $data['status']  = $status;
	        $data['reload']  = $reload;
	        echo json_encode($data);
    	}
	}
		

}