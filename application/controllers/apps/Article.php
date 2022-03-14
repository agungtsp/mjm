<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('articlemodel');
		$this->load->model('languagemodel');
	}
	function index(){
		$data['list_status_publish'] = selectlist2(array('table'=>'status_publish','title'=>'All Status','selected'=>$data['id_status_publish']));
		render('apps/article/index',$data,'apps');
	}
	
	public function add($id=''){
		if($id){
			// $data = $this->articlemodel->findById($id);
			$datas 	= $this->articlemodel->selectData($id);

            if(!$datas){
				die('404');
			}
			$data 				= quote_form($datas);
			$data['judul']		= 'Edit';
			$data['proses']		= 'Update';
		}
		else{
			$data['judul']				= 'Add';
			$data['proses']				= 'Simpan';
			$data['title']	= '';
			$data['teaser']	= '';
			$data['uri_path']	= '';
			// $data['description2']		= '';
			$data['background']			= '';
			$data['color']				= '';
			$data['description']		= '';
			$data['publish_date']		= date('d-m-Y');
			$data['id'] 				= '';
            $data['url'] 				= '';
			$data['checked_is_featured']     = '';
		}

		$data['list_lang']	= $this->languagemodel->langName();

		foreach($data['list_lang'] as $key => $value){
			$data['list_lang'][$key]['invis']			= ($key==0) ? '' : 'hide';
			$data['list_lang'][$key]['active']			= ($key==0) ? 'active in' : '';
			$data['list_lang'][$key]['validation']		= ($key==0) ? 'true' : 'false';
			$data['list_lang'][$key]['nomor']			= $key;
			$data['list_lang'][$key]['checked_is_featured']      = ($datas[$key]['is_featured']==1)?"checked":'';
			$data['list_lang'][$key]['title'] 		= $datas[$key]['title'];
			$data['list_lang'][$key]['teaser'] 		= $datas[$key]['teaser'];
			$data['list_lang'][$key]['uri_path'] 		= $datas[$key]['uri_path'];
			$data['list_lang'][$key]['type'] 		= $datas[$key]['type'];
			$data['list_lang'][$key]['url'] 					= $datas[$key]['url'];
			$data['list_lang'][$key]['publish_date'] 			= iso_date($datas[$key]['publish_date']);
			$data['list_lang'][$key]['description'] 			= $datas[$key]['description'];
			$data['list_lang'][$key]['id_status_publish'] 		= $datas[$key]['id_status_publish'];
			$data['list_lang'][$key]['id_lang'] 				= $datas[$key]['id_lang'];
			$data['list_lang'][$key]['id'] 						= $datas[$key]['id'];

			$data['list_lang'][$key]['list_status_publish'] 	= selectlist2(array('table'=>'status_publish','title'=>'Select Status','selected'=>$datas[$key]['id_status_publish']));
			
			$img_thumb											= image($datas[$key]['img'],'large');
			$imagemanager										= imagemanager('img',$img_thumb,400,230,$key);
			$data['list_lang'][$key]['img']						= $imagemanager['browse'];
			$data['list_lang'][$key]['imagemanager_config']		= $imagemanager['config'];
		}

		$data['list_lang2']		= $data['list_lang'];
		render('apps/article/add',$data,'apps');
	}

	function records(){
		$data = $this->articlemodel->records();
		foreach ($data['data'] as $key => $value) {
			$data['data'][$key]['title'] 	= quote_form($value['title']);
			$data['data'][$key]['publish_date'] 	= iso_date($value['publish_date']);
			$data['data'][$key]['approval_level'] 	= $approval;
		}
		render('apps/article/records',$data,'blank');
	}
	
	
	function proses($idedit=''){
		$id_user 				=  id_user();
		$this->layout 			= 'none';
		$post 					= purify($this->input->post());
		$ret['error']			= 1;
		$id_parent_lang 		= NULL;
		$this->db->trans_start();

		foreach ($post['title'] as $key => $value){
			$this->form_validation->set_rules('id_status_publish[0]', '"Status Publish"', 'required'); 
			if ($this->form_validation->run() == FALSE){
				$ret['message']  = validation_errors(' ',' ');
			}else{
				if($key==0){
					if($idedit){
						$data_save['is_featured']	= ($post['is_featured'][$key] != 1) ? 0 : 1;
					}
					$idedit 			= $post['id'][$key];
				 	$is_featured 		= ($post['is_featured'][$key] != 1) ? 0 : 1;
				 	$publish_date		= iso_date($post['publish_date'][$key]);
				 	$id_status_publish	= $post['id_status_publish'][$key];
				}

				$data_save['title'] 		= $post['title'][$key];
				$data_save['teaser'] 		= $post['teaser'][$key];
				$data_save['uri_path'] 		= $post['uri_path'][$key];
				$data_save['url'] 					= $post['url'][$key];
				$data_save['publish_date']			= $publish_date;
				$data_save['description'] 			= $post['description'][$key];
				$data_save['is_featured'] 			= $is_featured;
				$data_save['id_status_publish'] 	= $id_status_publish;
				$data_save['id_lang'] 				= $post['id_lang'][$key];
				$data_save['id_parent_lang']		= $id_parent_lang;
				$data_save['id'] 					= $post['id'][$key];
				
				if($idedit && $post['img'][$key]){
					$data_save['img']	= $post['img'][$key];
				}elseif($idedit){
					$datas 				= $this->articlemodel->selectData($idedit);
					$data_save['img']	= $datas[$key]['img'];
				}else{
					$data_save['img']	= $post['img'][$key];
				}

				if($idedit){
					if($key==0){
						auth_update();
						$ret['message'] = 'Update Success';
						$act			= "Update article";
						$iddata 		= $this->articlemodel->update($data_save,$idedit);
					}else{
						auth_update();
						$ret['message'] = 'Update Success';
						$act			= "Update article";
						$iddata 		= $this->articlemodel->updateKedua($data_save,$idedit);
					}
				}else{
					auth_insert();
					$ret['message'] = 'Insert Success';
					$act			= "Insert article";
					$iddata 		= $this->articlemodel->insert($data_save);
				}

				if($key==0){
					$id_parent_lang = $iddata;
				}

				$this->db->trans_complete();
				set_flash_session('message',$ret['message']);
				$ret['error'] = 0;
			}
		}
		echo json_encode($ret);
	}
	function del(){
		$this->db->trans_start();   
		$id = $this->input->post('iddel');
		$this->articlemodel->delete($id);
		$this->articlemodel->delete2($id);
		$this->db->trans_complete();
	}
	
}

/* End of file article.php */
/* Location: ./application/controllers/apps/article.php */