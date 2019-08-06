<?php
	/**
	* Main Model
	*/
	class Main_model extends MY_Model{
		
		function __construct(){
			parent::__construct();
			$this->table = 'options';
			$this->key = 'option_id';
			$this->order = array('option_id', 'DESC');
        }
        
        function get_option($key){
            $result = $this->get_info_rule(array('option_name' => $key), 'option_value');
			return $result->option_value;
		}

		function update_option($key, $value) {
			$result = $this->get_info_rule(array('option_name' => $key), 'option_value');
			if($result){
				$return = $this->update_rule(array('option_name' => $key), array('option_value' => $value));
			} else {
				$return =  $this->create(array('option_name' => $key, 'option_value' => $key));
			}
			if($return) return true; else return false;
		}

		function getNameUser($id){
			$this->db->select('u_name');
			$this->db->where('u_id', $id);
			$query = $this->db->get('user');
			if ($query->row())  {
				return $query->row()->u_name;
			}
			return FALSE;
		}

		function getNameLink($id){
			$this->db->select('u_link');
			$this->db->where('u_id', $id);
			$query = $this->db->get('user');
			if ($query->row())  {
				return $query->row()->u_link;
			}
			return FALSE;
		}

		function getLastBuildDate(){
			$this->db->select('p_update');
			$this->db->where('p_status', '4');
			$this->db->order_by('p_update', 'DESC');
			$query = $this->db->get('posts');
			if ($query->row())  {
				return $query->row()->p_update;
			}
			return FALSE;
		}
    }