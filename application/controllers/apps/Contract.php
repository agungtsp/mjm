<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contract extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('contract_model');
	}

	function index(){
		$data['list_status_publish']   = selectlist2(array('table'=>'status_publish','title'=>'All Status','selected'=>$data['id_status_publish']));
		$data['list_auth_user']   = selectlist2(array('id'=>'id_auth_user','name'=>'full_name', 'where' => 'id_auth_user_grup=6', 'table'=>'auth_user','title'=>'All Client','selected'=>$data['id_auth_user']));
		$data['list_product_category']   = selectlist2(array('table'=>'product_category','title'=>'All Product Category','selected'=>$data['id_product_category']));
		
		render('apps/contract/index',$data,'apps');
	}

	public function add($id=''){
		if($id){
			$data = $this->contract_model->findById($id);
			if(!$data){
				die('404');
			}
			$data 					= quote_form($data);
			$data['judul']			= 'Edit';
			$data['proses']			= 'Update';
		}else{
			$data['judul']			= 'Add';
			$data['proses']			= 'Save';
			$data['name'] 			= '';
			$data['uri_path']		= '';
			$data['teaser']			= '';
			$data['value']	= '';
			$data['id'] 			= '';
			$data['address'] 		= '';
			$data['number'] 		= '';
			$data['email'] 			= '';
			$data['website'] 		= '';
			$data['type'] = '';
			$data['detail'] = '';
			$data['license'] = '';
			$data['company_name'] = '';
			$data['pic_name'] = '';
			$data['email'] = '';
			$data['office_number'] = '';
			$data['whatsapp_number'] = '';
			$data['status'] = 'Need Review';
			
		}
		$data['list_auth_user']   = selectlist2(array('id'=>'id_auth_user','name'=>'full_name', 'where' => 'id_auth_user_grup=6', 'table'=>'auth_user','title'=>'All Client','selected'=>$data['id_auth_user']));
		$data['list_product_category']   = selectlist2(array('table'=>'product_category','title'=>'All Product Category','selected'=>$data['id_product_category']));
		$data['selected_odm'] = ($data['type']=='ODM') ? "selected" : "";
		$data['selected_oem'] = ($data['type']=='OEM') ? "selected" : "";
		render('apps/contract/add',$data,'apps');
	}

	public function view($id=''){
		if($id){
			$data = $this->contract_model->findById($id);

			$data['img_thumb'] 	= image($data['img'],'small');
			$data['img_ori'] 	= image($data['img'],'large');
			if(!$data){
				die('404');
			}
			$data['page_name'] 	= quote_form($data['page_name']);
			$data['teaser'] 	= quote_form($data['teaser']);
		}
		render('apps/contract/view',$data,'apps');
	}

	function records(){
		$data = $this->contract_model->records();		
		render('apps/contract/records',$data,'blank');
	}

	function proses($idedit=''){
		$this->layout         = 'none';
		$post                 = purify($this->input->post());
		$ret['error']         = 1;

		$this->form_validation->set_rules('id_auth_user', '"User"', 'required');
		$this->form_validation->set_rules('type', '"type"', 'required');
		$this->form_validation->set_rules('detail', '"detail"', 'required');
		$this->form_validation->set_rules('license', '"license"', 'required');
		$this->form_validation->set_rules('company_name', '"company_name"', 'required');
		$this->form_validation->set_rules('pic_name', '"pic_name"', 'required');
		$this->form_validation->set_rules('email', '"email"', 'required');
		$this->form_validation->set_rules('office_number', '"office_number"', 'required');
		$this->form_validation->set_rules('whatsapp_number', '"whatsapp_number"', 'required');
		$this->form_validation->set_rules('status', '"status"', 'required');
		if ($this->form_validation->run() == FALSE){
			$ret['message']  = validation_errors(' ',' ');
		}else{
			$this->db->trans_start();

			if($idedit){
				auth_update();
				$ret['message'] = 'Update Success';
				$act			= "Update contract";
				$this->contract_model->update($post,$idedit);
			}else{
				auth_insert();
				$ret['message'] = 'Insert Success';
				$act			= "Insert contract";
				$this->contract_model->insert($post);
			}

			detail_log();
			insert_log($act);
			$this->db->trans_complete();

			$this->session->set_flashdata('message',$ret['message']);
			$ret['error'] = 0;
		}
		echo json_encode($ret);
	}

	function del(){
		auth_delete();

		$id 	= $this->input->post('iddel');
		$data 	= $this->contract_model->delete($id);
		detail_log();
		insert_log("Delete contract");
	}

}

/* End of file contract.php */
/* Location: ./application/controllers/apps/contract.php */