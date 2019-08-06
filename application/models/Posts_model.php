<?php
	/**
	* Posts Model
	*/
	class Posts_model extends MY_Model{		
		function __construct(){
			parent::__construct();
			$this->table = 'posts';
			$this->key = 'p_id';
			$this->order = array('p_id', 'DESC');
		}

		function get_list_relationships($input = array(), $user_id, $role)	{
			if($role > 2){
				$rela = array();
				$relationships = $this->posts_get_relationships2($user_id);
				if($relationships){
					foreach ($relationships as $value) {
						$rela[] = $value->object_id;
					}
				}				
			}

		    //xu ly ca du lieu dau vao
			$this->get_list_set_input($input);
			if($role > 2 && isset($relationships) && $relationships != Null){
				$this->db->where_in('p_id', $rela);
			}

			//thuc hien truy van du lieu
			$query = $this->db->get($this->table);
			//echo $this->db->last_query();
			return $query->result();
		}

		function posts_check_relationships($postid, $user_id){
			$this->db->where(array("object_id" => $postid, "target_id" => $user_id));
		    //thuc hien cau truy van lay du lieu
			$query = $this->db->get("relationships");
			
			if($query->num_rows() > 0){
				return TRUE;
			}else{
				return FALSE;
			}
		}

		function total_doanhthu_moment($id, $start = '', $end = ''){
			$this->db->select('pm_value');
			$this->db->where('pm_p_id', $id);
			$query = $this->db->get('post_meta');
			$total = 0;
			if ($query->result()){
				foreach ($query->result() as $key => $value) {
					$total += $value->pm_value;
				}
				return $total.'/'.count($query->result());
			}
			return '';	
		}

		function total_doanhthu($id, $start = '', $end = ''){
			$this->db->select('pm_value');
			$where = array('pm_p_id' => $id);
			if($start != ''){
				$time = date('Y-m-d', strtotime('01-'.$start));
				$where['pm_time >='] = $time;
			}
			if($end != ''){
				$time = date('Y-m-d', strtotime('01-'.$end));
				$where['pm_time <='] = $time;
			}
			$this->db->where($where);
			$query = $this->db->get('post_meta');
			$total = 0;
			if ($query->result()){
				foreach ($query->result() as $key => $value) {
					$total += $value->pm_value;
				}
				return $total;
			}
			return '';	
		}

		function delete_posts($id){

		}

		function clear_post_meta($id){
			if (!$id) {
				return FALSE;
			}
			$where = array('pm_p_id' => $id);
			$this->db->where($where);
			if($this->db->delete('post_meta')){
				return TRUE;
			}
		}

		function posts_insert_relationships($user, $id){
			return $this->db->insert('relationships', array('object_id' => $id, 'target_id' => $user));
		}

		function posts_get_relationships($id){
			$this->db->select('*');
			$this->db->where('object_id', $id);
			$this->db->join('user', 'target_id = u_id');
			$query = $this->db->get('relationships');
			if ($query->result())  {
				return $query->result();
			}
			return FALSE;
		}

		function posts_get_relationships2($id){
			$this->db->select('object_id');
			$this->db->where('target_id', $id);
			$query = $this->db->get('relationships');
			if ($query->result())  {
				return $query->result();
			}
			return FALSE;
		}

		function posts_clear_relationships($id){
			if (!$id) {
				return FALSE;
			}
			$where = array('object_id' => $id);
			$this->db->where($where);
			if($this->db->delete('relationships')){
				return TRUE;
			}
		}

	}