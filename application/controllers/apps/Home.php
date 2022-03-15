<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->layout = 'none';
		$this->load->model('homeadminmodel');
		$this->load->model('authgroup_model','authGroupModel');
		$this->load->model('model_user','userModel');
	}
	function index(){
	    $_SESSION['adminLogin'] = true;
		render('apps/home/home',$data,'apps');
	}

	function check_valid_file() {
		$file = purify($this->input->post());
		$file_size = $_FILES['userfile']['size'];
		$file_type = $_FILES['userfile']['type'];
		// print_r($_FILES);exit();
        if (intval($file_size/1000000) <= 50) {
        	if (
        		$file_type == 'application/pdf' || 
	        	$file_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ||
	        	$file_type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ||
	        	$file_type == 'application/msword'||
	        	$file_type == 'application/vnd.ms-excel'
	        ) {
				$ret['error']   = 0;
				$ret['message'] = 'success';
        	} else {
        		$ret['error']   = 1;
				$ret['message'] = 'The filetype you are attempting to upload is not allowed.';
        	}

        }
        else {  
			$ret['error']   = 1;
			$ret['message'] = 'File Size Max 20Mb';
        }

        echo json_encode($ret);
        exit();
	}
	
	function imagemanager(){
		$post = purify($this->input->post());
		$tglsearch = $post['searchDate'];
		$file = $_FILES;
		if($file){
			$file 	= $_FILES['img'];
	        $fname 	= $file['name'];
	        // $ext	= explode('.',$fname);
	        // $ext	= '.'.$ext[count($ext)-1];
			$maxFileSize = MAX_UPLOAD_SIZE * 1024 * 1024;
	        if(!is_writable(UPLOAD_DIR)){//kalo ga bisa upload
	            $ret['error']   = 1;
	            $ret['message'] = "Directory is readonly";
	        } else if($file['size']>=$maxFileSize){
				$ret['error']   = 1;
	            $ret['message'] = "Max File size is ".MAX_UPLOAD_SIZE."MB";
			}
	        else if($fname){
	            // $folder=UPLOAD_DIR.'temp/';
	            // if(!file_exists($folder)){//kalo blm ada foldernya, bikin dulu
	            //     mkdir($folder);
	            // }
	            // $new_file = rand(1000,9999).$ext;
	            // move_uploaded_file($file['tmp_name'],$folder.$new_file);
	            $upload = upload_file('img','temp');
	            $ret['filename'] = $this->baseUrl."images/article/temp/".$upload['file_name'];
	            $ret['file'] = $upload['file_name'];
				$ret['size'] = $upload['file_size'];
	            $ret['width']= $upload['image_width'];
	            $ret['height']= $upload['image_height'];
	            $ret['message'] = 'success';
	        }
	        echo json_encode($ret);
	        exit;
		}
		$this->load->model('filemanagermodel');
		if($post['id']) { //delete image
			$this->filemanagermodel->delete($post['id'], array('user_id_modify'=>id_user(),'modify_date'=>date('Y-m-d H:i:s')));
		}
		$total_records = $this->filemanagermodel->getTotal("(user_id_create = ".id_user() ." or is_public = 1) and name LIKE '%".$post['searchPicture']."%' and create_date LIKE '%".$tglsearch."%'");
		$per_page = 12;
		$data['pages'] = ceil($total_records/$per_page);
		$data['load'] = base_url().'apps/home/imagemanager';
		// $data['search'] = base_url().'apps/home/search';

		//sanitize post value
		if(isset($post['page'])){
			$page_number = filter_var($post["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
			if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
		}else{
			$page_number = 1;
		}

		//get current starting point of records
		$offset = (($page_number-1) * $per_page);


		$data['list_data'] = $this->filemanagermodel->getAll("(user_id_create = ".id_user() ." or is_public = 1) and name LIKE '%".$post['searchPicture']."%' and create_date LIKE '%".$tglsearch."%'", $per_page, $offset);
		render('apps/filemanager',$data,'blank');

	}
	function imagemanager_save(){
		$post    = $this->input->post();
		$tmp     = $_SERVER['DOCUMENT_ROOT'].$this->baseUrl.'external/'.$post['tmp'];
		$thumbs  =  UPLOAD_DIR.'small/'.$post['name'];
		$ori_tmp =  UPLOAD_DIR.'temp/'.$post['name'];
		$ori     =  UPLOAD_DIR.'large/'.$post['name'];
		rename($tmp,$thumbs);
		rename($ori_tmp,$ori);
		unset($post['tmp']);
		$post['user_id_create'] = id_user();
		$this->load->model('filemanagermodel');
		$this->filemanagermodel->insert($post);
	}
}