<?php
	/**
	* User Model
	*/
	class User_model extends MY_Model{
		
		function __construct(){
			parent::__construct();
			$this->table = 'user';
			$this->key = 'u_id';
			$this->order = array('u_id', 'ASC');
		}

		function insert_relationships($data = array(), $id){
			foreach ($data as $key => $value) {
				$this->db->insert('relationships_user', array('object_id' => $id, 'target_id' => $value));
			}
		}

		function get_relationships($id){
			$this->db->select('target_id');
			$this->db->where('object_id', $id);
			$query = $this->db->get('relationships_user');
			if ($query->result())  {
				return $query->result();
			}
			return FALSE;
		}

		function clear_relationships($id){
			if (!$id) {
				return FALSE;
			}
			$where = array('object_id' => $id);
			$this->db->where($where);
			if($this->db->delete('relationships_user')){
				return TRUE;
			}
		}

		function get_relationships_name($id){
			$this->db->select('taxonomy_name');
			$this->db->where('object_id', $id);
			$this->db->join('taxonomy', 'target_id = taxonomy_id');
			$query = $this->db->get('relationships_user');
			if ($query->result())  {
				return $query->result();
			}
			return FALSE;
		}

		function delete_posts($id){		
			
		}

		function checkUser($data = array()){
			$where = array('u_email' => $data->u_email);
			if($this->check_exists($where)){
				$userInfo = $this->get_info_rule($where);
				//$userID = $userInfo->u_id;
				//$input = array('u_name' => $data->u_name, 'u_appID' => $data->u_appID, 'u_img' => $data->u_img, 'u_type' => $data->u_type);
				//$update = $this->update($userID, $input);
			}else{
				$input = array('u_name' => $data->u_name, 'u_email' => $data->u_email, 'u_img' => $data->u_img, 'u_appID' => $data->u_appID);
				$add = $this->create($input);
				$userInfo = $this->get_info_rule($where);
			}

			return $userInfo?$userInfo:FALSE;
		}

		function user_clear_relationships($id){
			if (!$id) {
				return FALSE;
			}
			$where = array('target_id' => $id);
			$this->db->where($where);
			if($this->db->delete('relationships')){
				return TRUE;
			}
		}

	}