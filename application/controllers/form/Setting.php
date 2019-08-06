<?php

class Setting extends MY_Controller{

	function __construct() {
		parent::__construct();
		$user_info = $this->session->userdata('login');
		if(!Per_checkController('setting', $user_info->u_point)){
			echo "Bạn không có quyền truy cập trang này !";
			exit();
		}
    }

    //Setting General
	function general(){		
		$data = $this->input->post('data', false);
		foreach ($data as $key => $value) {
			$this->main_model->update_option($key, $value);
		}
		$this->session->set_flashdata('msgsuccess', 'Cập nhật thành công !');
		echo 1;
	}
	//Setting Logo
	function logo(){		
		if(isset($_FILES['data'])) {
			// Upload Image
			if(isset($_FILES['data']['name']['favicon']) && $_FILES['data']['name']['favicon'] != ''){
				// File name
				$fileName = $_FILES['data']['name']['favicon'];
				// File size
				$fileSize = $_FILES['data']['size']['favicon'];
				// File TMP
				$fileTemp = $_FILES['data']['tmp_name']['favicon'];
				// File Type
				$fileType = $_FILES['data']['type']['favicon'];
				// File Error
				$fileError = $_FILES['data']['error']['favicon'];
				$ext = explode('.', $fileName);
				$ext = strtolower(end($ext));
				$fileName = md5_file($fileTemp).time().'.'.$ext;

				if (!preg_match("/.(gif|jpg|png)$/i", $fileName) ) {
					echo "Lỗi: Loại ảnh cho phép (.gif, .jpg, .png).";
					unlink($fileTemp);
					exit();
				} else if($fileSize > (1024*1024*3)) {
					echo "Lỗi: Ảnh phải nhỏ hơn 3MB.";
					unlink($fileTemp);
					exit();
				} else if ($fileError == 1) {
					echo "Lỗi: Lỗi trong quá trình xử lý ảnh, vui lòng thử lại sau.";
					exit();
				}
				$moveResult = move_uploaded_file($fileTemp, "uploads/images/".$fileName);
				if ($moveResult != true) {
					echo "Lỗi: Không thể upload.";
					exit();
				}

				$this->main_model->update_option('_favicon', $fileName);
				
			}

			if(isset($_FILES['data']['name']['logo']) && $_FILES['data']['name']['logo'] != ''){
				// File name
				$fileName = $_FILES['data']['name']['logo'];
				// File size
				$fileSize = $_FILES['data']['size']['logo'];
				// File TMP
				$fileTemp = $_FILES['data']['tmp_name']['logo'];
				// File Type
				$fileType = $_FILES['data']['type']['logo'];
				// File Error
				$fileError = $_FILES['data']['error']['logo'];
				$ext = explode('.', $fileName);
				$ext = strtolower(end($ext));
				$fileName = md5_file($fileTemp).time().'.'.$ext;

				if (!preg_match("/.(gif|jpg|png)$/i", $fileName) ) {
					echo "Lỗi: Loại ảnh cho phép (.gif, .jpg, .png).";
					unlink($fileTemp);
					exit();
				} else if($fileSize > (1024*1024*3)) {
					echo "Lỗi: Ảnh phải nhỏ hơn 3MB.";
					unlink($fileTemp);
					exit();
				} else if ($fileError == 1) {
					echo "Lỗi: Lỗi trong quá trình xử lý ảnh, vui lòng thử lại sau.";
					exit();
				}
				$moveResult = move_uploaded_file($fileTemp, "uploads/images/".$fileName);
				if ($moveResult != true) {
					echo "Lỗi: Không thể upload.";
					exit();
				}
				
				$this->main_model->update_option('_logo', $fileName);
				
			}			
			$this->session->set_flashdata('msgsuccess', 'Cập nhật thành công !');
			echo '1';
			exit();
		}else {
			return false;
		}
    }
    
    // Thêm network
	function network_add() {
		$this->load->model('network_model');
		$data = $this->input->post('data');
		if(isset($data)) {
			if(isset($data['name']) && $data['name'] == '') {
				echo 'Tên không được bỏ trống';
				exit();
			}
			if(!empty($data['order'])) {
				if(!is_numeric($data['order'])) {
					echo "Lỗi: Vị trí phải là số.";
					exit();
				}
			}
			
			//Xử lý dữ liệu
			$name = $this->db->escape_str($data['name']);
			$order = (isset($data['order']) && $data['order'] != '')  ? $data['order'] : 99;

			$input = array('n_name' => $name, 'n_order' => $order);
			
			$add = $this->network_model->create($input);

			$id = $this->db->insert_id();

			if($add) {
				$this->session->set_flashdata('msgsuccess', 'Thêm  thành công !');
				echo $id;
			} else {
				$this->session->set_flashdata('msgerror', 'Lỗi, xin thử lại sau!');
				echo 'Error';
			}
			exit();
		} else {
			return false;
		}
	}
	// Chỉnh sửa network
	function network_edit() {
		$this->load->model('network_model');
		$data = $this->input->post('data');
		if(isset($data)) {
			if(isset($data['name']) && $data['name'] == '') {
				echo 'Tên không được bỏ trống';
				exit();
			}
			if(!empty($data['order'])) {
				if(!is_numeric($data['order'])) {
					echo "Lỗi: Vị trí phải là số.";
					exit();
				}
			}
			//Xử lý dữ liệu
			$id = $data['postid'];
			$name = $this->db->escape_str($data['name']);
			$order = isset($data['order']) ? $data['order'] : 99;
			
			$input = array('n_name' => $name, 'n_order' => $order);
			
			$edit = $this->network_model->update($id, $input);

			if($edit) {
				$this->session->set_flashdata('msgsuccess', 'Chỉnh sửa thành công !');
				echo $id;
			} else {
				$this->session->set_flashdata('msgerror', 'Lỗi, xin thử lại sau!');
				echo 'Error';
			}
			exit();
		} else {
			return false;
		}
    }

    //Xóa network
    function network_delete() {
		$this->load->model('network_model');
		$data = $this->input->post('delete_post');
		if(isset($data) && is_numeric($data)) {
			$del = $this->network_model->delete($data);
			exit();
		} else {
			echo "Lỗi chưa có tham số !";
			exit();
		}
	}
}