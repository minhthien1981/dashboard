<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class My_model extends CI_Model {
	
	// Tên table
	var $table = '';
	
	// Key chính của table
	var $key = 'id';
	
	// Order mặc định (VD: $order = array('id', 'desc))
	// Sắp xếp mặt định
	var $order = '';
	
	// Các field select mặc định khi get list (VD: $select = 'id, name')
	var $select = '';
	/**
	 * Thêm row mới
	 * $data : dữ liệu mà ta cần thêm
	 */
	function create($data = array()) {
		if($this->db->insert($this->table, $data)){
		   return TRUE;
		}else{
			return FALSE;
		}
	}


	/**
	 * Cap nhat row tu id
	 * $id : khoa chinh cua bang can sua
	 * $data : mang du lieu can sua
	 */
	function update($id, $data)	{
		if (!$id) {
			return FALSE;
		}
				
		$where = array();
	 	$where[$this->key] = $id;
	    $this->update_rule($where, $data);
	 	
	 	return TRUE;
	}
 
    /**
    * Cap nhat row tu dieu kien
    * $where: điều kiện
    */
    function update_rule($where, $data) {
        if (!$where) {
            return FALSE;
        }
        //thêm điều kiện
        $this->db->where($where);
        //cập nhật dữ liệu
        if($this->db->update($this->table, $data)) {
            return TRUE;
        }
        return FALSE;
    }

	/**
	 * Xoa row tu id
	 * $id : gia tri cua khoa chinh
	 */
	function delete($id) {
		if (!$id) {
			return FALSE;
		}
		//neu la so
		if(is_numeric($id)) {
			$where = array($this->key => $id);
		}else {
		    //xoa nhieu row
		    //$id = 1,2,3...
			$where = $this->key . " IN (".$id.") ";
		}
	 	$this->del_rule($where);
		
		return TRUE;
	}

    /**
    * Xoa row tu dieu kien
    */
    function del_rule($where) {
        if (!$where) {
            return FALSE;
        }
        $this->db->where($where);//thêm điều kiện
        //thực hiện xóa
        if($this->db->delete($this->table)){
            return TRUE;
        }
        return FALSE;
    }

	/**
	 * Lay thong tin cua row tu id
	 * $id : id can lay thong tin
	 * $field : cot du lieu ma can lay
	 */
	function get_info($id, $field = '')	{		
		if (!$id){
			return FALSE;
		}
	 	
	 	$where = array();
	 	$where[$this->key] = $id;
	 	
	 	return $this->get_info_rule($where, $field);
	}
 
    /**
     * Lay thong tin cua row tu dieu kien
     * $where: Mảng điều kiện
     */
    function get_info_rule($where = array(), $field='')  {
    	if($field){
			$this->db->select($field);
		}
        $this->db->where($where);
        $query = $this->db->get($this->table);
        if ($query->num_rows())  {
            return $query->row();
        }
        return FALSE;
    }
	/**
	 * Lay tong so
	 */
	function get_total($input = array()) {
		$this->get_list_set_input($input);
		
		$query = $this->db->get($this->table);
		
		return $query->num_rows();
	}
		
	/**
	 * Lay danh sach
	 * $input : mang cac du lieu dau vao
	 */
	function get_list($input = array())	{
	    //xu ly ca du lieu dau vao
		$this->get_list_set_input($input);
		
		//thuc hien truy van du lieu
		$query = $this->db->get($this->table);
		//echo $this->db->last_query();
		return $query->result();
	}

	/**
    * Gan cac thuoc tinh trong input khi lay danh sach
    */
    protected function get_list_set_input($input)  {
        // Select
	    if (isset($input['select'])){
	        $this->db->select($input['select']);
	    }
        // Thêm điều kiện cho câu truy vấn truyền qua biến $input['where']
 
        if ((isset($input['where'])) && $input['where']) {
            $this->db->where($input['where']);
        }

        // Kết bảng cho câu truy vấn truyền qua biến $input['join']
        if (isset($input['join'][0]) && isset($input['join'][1])){
            $this->db->join($input['join'][0], $input['join'][1]);
        }
        
		// Thêm sắp xếp dữ liệu thông qua biến $input['order'] (ví dụ $input['order'] = array('id','DESC'))
        if (isset($input['order'][0]) && isset($input['order'][1]))     {
            $this->db->order_by($input['order'][0], $input['order'][1]);
        } else {
            //mặc định sẽ sắp xếp theo id giảm dần
            $this->db->order_by($this->key, 'DESC');
        }
 
        // Thêm điều kiện limit cho câu truy vấn thông qua biến $input['limit'] (ví dụ $input['limit'] = array('10' ,'0'))
        if (isset($input['limit'][0]) && isset($input['limit'][1])) {
            $this->db->limit($input['limit'][0], $input['limit'][1]);
        }
 
    }
	
	/**
	 * kiểm tra sự tồn tại của dữ liệu theo 1 điều kiện nào đó
	 * $where : mang du lieu dieu kien
	 */
    function check_exists($where = array())  {
	    $this->db->where($where);
	    //thuc hien cau truy van lay du lieu
		$query = $this->db->get($this->table);
		
		if($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
}
?>