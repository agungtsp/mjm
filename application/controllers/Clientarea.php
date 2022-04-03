<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clientarea extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('usermodel');
		$this->load->library("session");
		$this->load->model('contract_model');
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
    		// $this->form_validation->set_rules('product_category[]', '"Product Category"', 'required'); 
    		// $this->form_validation->set_rules('type_of_product', '"Type of Product"', 'required'); 
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
		if(!$this->session->userdata('MEM_SESS')){
			redirect(base_url_lang()."/clientarea");
		}
		$data = $this->usermodel->findById(id_member());
		$data['page_name'] = 'Profile';
		if($data['seo_title'] == ''){
			$data['seo_title'] = "MJM | Partner for Quality";
		}
		$data['meta_description'] = preg_replace('/<[^>]*>/', '', $data['meta_description']);

		$id_lang = id_lang();
        $this->load->model('pagesmodel');
        $filter_pages['id_lang'] = $id_lang;
        $filter_pages['lower(title)'] = 'dashboard member';
        $page = $this->pagesmodel->findBy($filter_pages, 1);
        $data['dashboard_description'] = str_replace("{base_url}", base_url(), $page['description']);	
        $data['dashboard_title'] = $page['title'];
		$data['dashboard_img'] = getImgLink($page['img'], 'large');
		$data['member_dashboard_active'] = "active";

		$this->load->model('promotionmodel');
        $filter_list_promotion['id_lang'] = $id_lang;
        $data['list_promotions'] = $this->promotionmodel->findBy($filter_list_promotions);	
        foreach ($data['list_promotions'] as $key => $value) {
            $data['list_promotions'][$key]['img'] = getImgLink($value['img'], 'large');
        }

		$filter_pages['id_lang'] = $id_lang;
        $filter_pages['lower(title)'] = 'mjm contract manufacture';
        $page = $this->pagesmodel->findBy($filter_pages, 1);
        $data['mjm_contract_description'] = str_replace("{base_url}", base_url(), $page['description']);	
        $data['mjm_contract_title'] = $page['title'];
		$data['mjm_contract_img'] = getImgLink($page['img'], 'large');
		$data['member_mjm_contract_active'] = "active";

		$data['list_top_event'] = $this->top_event($id_lang);

		$data['list_product_category']   = selectlist2(array('table'=>'product_category','title'=>'All Product Category','selected'=>$data['id_product_category']));

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
		
	private function top_event($id_lang, $id=''){
        $this->load->model('eventmodel');

        $filter_recomendation['id_lang'] = $id_lang;
        if($id){
            $filter_recomendation['id !='] = $id;
        } 
		$filter_recomendation['is_public'] = 0;
        $this->db->order_by('event_date', 'desc');
        $data = $this->eventmodel->findBy($filter_recomendation);	
        foreach ($data as $key => $value) {
            $data[$key]['img_top_event'] = getImgLink($value['img'], 'large');
            $data[$key]['uri_path_top_event'] = $value['uri_path'];
            $data[$key]['title_top_event'] = $value['title'];
            $data[$key]['url_top_event'] = $value['url'];
            $data[$key]['register_url_top_event'] = $value['register_url'];
            $data[$key]['event_date'] = date("d F Y", strtotime($value['event_date']));
        }
        return $data;
    }

	function logout(){
        $this->session->sess_destroy();
        redirect('');
    }

	function get_data_contract($sort){
		$mem_sess = $this->session->userdata('MEM_SESS');
		$data['html'] = '';
		$data['total'] = 0;

		if($mem_sess){
			$this->layout = 'blank';
			$where['a.id_auth_user'] = $mem_sess['id'];
			if($sort=="license"){
				$this->db->order_by('license', 'desc');
			} else {
				$this->db->order_by('create_date', 'desc');
			}
			$data_contract = $this->contract_model->findBy($where);
			foreach ($data_contract as $key => $value) {
				$data['html'] .= '<tr>
					<td>'.$value['license'].'</td>
					<td>'.date('d F Y', strtotime($value['create_date'])).'</td>
					<td>'.$value['type'].'</td>
					<td>'.$value['status'].'</td>
				</tr>';
			}
			$data['total'] = count($data_contract);
		}
		echo json_encode($data);
	}

	function submit_contract($idedit){
		$this->layout         = 'none';
		$post                 = purify($this->input->post());
		$ret['error']         = 1;
		$mem_sess = $this->session->userdata('MEM_SESS');
		if($mem_sess){
			$this->form_validation->set_rules('type[]', '"type"', 'required');
			$this->form_validation->set_rules('detail', '"detail"', 'required');
			$this->form_validation->set_rules('license', '"license"', 'required');
			$this->form_validation->set_rules('company_name', '"company_name"', 'required');
			$this->form_validation->set_rules('pic_name', '"pic_name"', 'required');
			$this->form_validation->set_rules('email', '"email"', 'required');
			$this->form_validation->set_rules('office_number', '"office_number"', 'required');
			$this->form_validation->set_rules('whatsapp_number', '"whatsapp_number"', 'required');
			if ($this->form_validation->run() == FALSE){
				$ret['message']  = validation_errors(' ',' ');
			}else{
				$this->db->trans_start();
				$post['type'] = $post['type'][0];
				$post['id_auth_user'] = $mem_sess['id'];
				$post['user_id_create'] = $mem_sess['id'];
				$post['status'] = 'Need Review';
				if($idedit){
					$ret['message'] = 'Update Success';
					$act			= "Update contract";
					$this->contract_model->update($post,$idedit);
				}else{
					$ret['message'] = 'Insert Success';
					$act			= "Insert contract";
					$this->contract_model->insert($post);
				}

				$this->db->trans_complete();
				$ret['error'] = 0;
			}
		}
		echo json_encode($ret);
	}


}