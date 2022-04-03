<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_category extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->model('product_category_model');
	}

	function index(){
		$data['list_status_publish']   = selectlist2(array('table'=>'status_publish','title'=>'All Status','selected'=>$data['id_status_publish']));
		
		render('apps/product_category/index',$data,'apps');
	}

	public function add($id=''){
		if($id){
			$data = $this->product_category_model->findById($id);
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
			
		}

		render('apps/product_category/add',$data,'apps');
	}

	public function view($id=''){
		if($id){
			$data = $this->product_category_model->findById($id);

			$data['img_thumb'] 	= image($data['img'],'small');
			$data['img_ori'] 	= image($data['img'],'large');
			if(!$data){
				die('404');
			}
			$data['page_name'] 	= quote_form($data['page_name']);
			$data['teaser'] 	= quote_form($data['teaser']);
		}
		render('apps/product_category/view',$data,'apps');
	}

	function records(){
		$data = $this->product_category_model->records();		
		render('apps/product_category/records',$data,'blank');
	}

	function proses($idedit=''){
		$this->layout         = 'none';
		$post                 = purify($this->input->post());
		$ret['error']         = 1;

		$this->form_validation->set_rules('name', '"Name "', 'required');
		$this->form_validation->set_rules('value', '"Value "', 'required');
		if ($this->form_validation->run() == FALSE){
			$ret['message']  = validation_errors(' ',' ');
		}else{
			$this->db->trans_start();

			if($idedit){
				auth_update();
				$ret['message'] = 'Update Success';
				$act			= "Update product_category";
				$this->product_category_model->update($post,$idedit);
			}else{
				auth_insert();
				$ret['message'] = 'Insert Success';
				$act			= "Insert product_category";
				$this->product_category_model->insert($post);
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
		$data 	= $this->product_category_model->delete($id);
		detail_log();
		insert_log("Delete product_category");
	}

}

/* End of file product_category.php */
/* Location: ./application/controllers/apps/product_category.php */