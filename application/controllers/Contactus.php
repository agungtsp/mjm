<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contactus extends CI_Controller {

	function __construct(){

		parent::__construct();

	}

     function index(){
    	$post   = purify($this->input->post());
		$reload = false;
    	if($post){
    		$this->form_validation->set_rules('name', '"Name"', 'required'); 
    		$this->form_validation->set_rules('email', '"Email"', 'required|valid_email'); 
    		$this->form_validation->set_rules('phone_number', '"Phone"', 'required'); 
    		$this->form_validation->set_rules('subject', '"Subject"', 'required'); 
    		$this->form_validation->set_rules('message', '"Message"', 'required'); 
			if ($this->form_validation->run() == FALSE){
				 $message = validation_errors();
				 $status  = 'error';
			} else {
				$this->load->model('contactusmodel');
				$post['contact_date'] = iso_date_custom_format(date('Y-m-d H:i:s'),'d-m-Y H:i:s');
				unset($post['g-recaptcha-response'], $post['action']);
				$proses               = $this->contactusmodel->insert($post);
				if($proses){
					sent_email_by_category(14,$post,'');
					$status  = 'success';
					$message = "Thanks, Your message  will be proses 5 day work.";
				} else{
					$status  = 'error';
					$message = "Sorry, Please Try Again proses";
					$reload = true;
				}
			}
	        $data['message'] = "$message";
	        $data['status']  = $status;
	        $data['reload']  = $reload;
	        echo json_encode($data);
    	} else{
    		$data['page_name'] = 'Contact Us';
    		if($data['seo_title'] == ''){
    		    $data['seo_title'] = "MJM | Partner for Quality";
    		}
    		$data['meta_description'] = preg_replace('/<[^>]*>/', '', $data['meta_description']);
			$data['active_contactus'] = 'active';
    		render('contactus',$data); 

    	}

    }

}