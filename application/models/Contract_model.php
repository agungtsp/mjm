<?php
class Contract_model extends  CI_Model{
	var $table = 'contract';
	var $tableAs = 'contract a';
	function __construct(){
		parent::__construct();

	}
	function records($where=array(), $isTotal=0){
		$alias['search_uri_path'] = 'a.uri_path';
		$alias['search_auth_user'] = 'b.id_auth_user';
		$alias['search_product_category'] = 'c.id';
		$alias['search_company_name'] = 'a.company_name';

	 	query_grid($alias,$isTotal);
		$this->db->select("a.*,b.full_name, c.name as product_category");
		$this->db->where('a.is_delete',0);
		$this->db->join('auth_user b',"b.id_auth_user = a.id_auth_user",'left');
		$this->db->join('product_category c',"c.id = a.id_product_category",'left');
		$query = $this->db->get_where($this->tableAs,$where);
		if($isTotal==0){
			$data = $query->result_array();
		}
		else{
			return $query->num_rows();
		}

		$ttl_row = $this->records($where,1);
		
		// echo $this->db->last_query();
		return ddi_grid($data,$ttl_row);
	}
	function insert($data){
		$data['create_date'] 	= date('Y-m-d H:i:s');
		if(id_user()){
			$data['user_id_create'] = id_user();
		}
		$data['is_delete'] = 0;
		$this->db->insert($this->table,$data);
		return $this->db->insert_id();
	}
	function update($data, $id){
		$where['id'] 			= $id;
		$data['user_id_modify'] = id_user();
		$data['modify_date'] 	= date('Y-m-d H:i:s');
		$this->db->update($this->table, $data, $where);
		return $id;
	}
	function updateKedua($data, $id){
		$where['id_parent_lang']	= $id;
		$data['user_id_modify'] 	= id_user();
		$data['modify_date'] 		= date('Y-m-d H:i:s');
		$this->db->update($this->table, $data, $where);
		return $id;
	}
	function delete($id){
		$data['is_delete'] = 1;
		$this->update($data,$id);
	}
	function delete2($id){
		$data = array(
			'is_delete' => 1,
			'user_id_modify' => id_user(),
			'modify_date' => date('Y-m-d H:i:s'),
		);
		$this->db->where('id_parent_lang', $id);
		$this->db->update($this->table, $data);
	}
	function findById($id){
		$where['a.id'] = $id;
		$where['is_delete'] = 0;
		return 	$this->db->get_where($this->table.' a', $where)->row_array();
	}
	function findBy($where,$is_single_row=0){
		$where['a.is_delete'] = 0;
		$this->db->select("a.*,b.full_name, c.name as product_category");
		$this->db->where('a.is_delete',0);
		$this->db->join('auth_user b',"b.id_auth_user = a.id_auth_user",'left');
		$this->db->join('product_category c',"c.id = a.id_product_category",'left');
		if($is_single_row==1){
			return 	$this->db->get_where($this->tableAs,$where)->row_array();
		}
		else{
			return 	$this->db->get_where($this->tableAs,$where)->result_array();
		}
	}
	function fetchRow($where) {
		return $this->findBy($where,1);
	}

	function selectData($id,$is_single_row=0)
	{
		$this->db->where('is_delete',0);
		$this->db->where('id_parent_lang', $id);
		$this->db->or_where('id', $id);
		if($is_single_row==1){
			return 	$this->db->get_where($this->table)->row_array();
		}
		else{
			return 	$this->db->get_where($this->table)->result_array();
		}
		// return $this->db->get($this->table)->result_array();
	}
}