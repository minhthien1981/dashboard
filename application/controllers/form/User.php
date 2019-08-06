<?php

class User extends MY_Controller{

	function __construct() {
		parent::__construct();
    }
    
    //Thêm sửa user
	function add(){
		$user_info = $this->session->userdata('login');
		if(!Per_checkAction('user', 'add', $user_info->u_point)){
			echo "Bạn không có quyền truy cập trang này !";
			exit();
		}
		$this->load->model('user_model');
		$data = $this->input->post('data');

		if(isset($data)) {
			// Validation
			if(isset($data['tennv']) && $data['tennv'] == '') {
				echo "Lỗi: Tên thành viên không được bỏ trống.";
				exit();
			}

			if(isset($data['usernv']) && $data['usernv'] != '') {
				$where = array('u_user' => $data['usernv']);
				if($this->user_model->check_exists($where)){
					echo "Lỗi: Tên tài khoản đã tồn tại.";
					exit();
				}
			}

			if(!empty($data['sdt'])) {
				if(!is_numeric($data['sdt'])) {
					echo "Lỗi: Số điện thoại phải là số.";
					exit();
				}else{
					$where = array('u_phone' => $data['sdt']);
					if($this->user_model->check_exists($where)){
						echo "Lỗi: Số điện thoại đã tồn tại.";
						exit();
					}
				}
			}

			if(isset($data['email']) && $data['email'] == '') {
				echo "Lỗi: Mật khẩu không được bỏ trống.";
				exit();
			}else{
				if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
					echo "Email không hợp lệ";
					exit();
				}else{
					$where = array('u_email' => $data['email']);
					if($this->user_model->check_exists($where)){
						echo "Lỗi: Email đã tồn tại.";
						exit();
					}
				}
			}

			// End validation
			
			// Upload Image
			if(isset($_FILES['data']['name']['hinhanh']) && $_FILES['data']['name']['hinhanh'] != '') {

				// File name
				$fileName = $_FILES['data']['name']['hinhanh'];
				// File size
				$fileSize = $_FILES['data']['size']['hinhanh'];
				// File TMP
				$fileTemp = $_FILES['data']['tmp_name']['hinhanh'];
				// File Type
				$fileType = $_FILES['data']['type']['hinhanh'];
				// File Error
				$fileError = $_FILES['data']['error']['hinhanh'];
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
				$moveResult = move_uploaded_file($fileTemp, "uploads/images/user/".$fileName);
				if ($moveResult != true) {
					echo "Lỗi: Không thể upload.";
					exit();
				}
				// Thumbnail
				$this->load->library('Process_images');
				$target_file = "uploads/images/user/".$fileName;
				$thumbnail_file = "uploads/images/user/thumbnail_".$fileName;
				$crop_file = "uploads/images/user/crop_".$fileName;
				$this->process_images->crop_image($target_file, $crop_file, THUMBNAIL_WIDTH, THUMBNAIL_HEIGHT, $ext);
				$this->process_images->ak_img_resize($crop_file, $thumbnail_file, THUMBNAIL_WIDTH, THUMBNAIL_HEIGHT, $ext);
				unlink($crop_file);

				$data['hinhanh'] = $fileName;
			}
			// End Upload Image

			// Point
			
			$point = array();
			if(!empty($data['point'])){
				foreach($data['point'] as $selected){
					$point[] = $selected;
				}
			}
			$point = array_unique($point);
			$data['point'] = 0;
			foreach ($point as $value) {
				$data['point'] += $value;
			}

			//Xử lý dữ liệu
			$tennhanvien = $this->db->escape_str($data['tennv']);
			$usernv = $this->db->escape_str($data['usernv']);
			$pass = isset($data['pass']) ? sha1(md5(sha1(addslashes($data['pass'])))) : '';
			$email = isset($data['email']) ? $this->db->escape_str($data['email']) : '';
			$sdt = isset($data['sdt']) ? $this->db->escape_str($data['sdt']) : '';
			$mota = isset($data['mota']) ? $data['mota'] : '';
			$hinhanh = isset($data['hinhanh']) ? $data['hinhanh'] : 'default.png';
            $point = isset($data['point']) ? $data['point'] : 1;
            $per = isset($data['per']) ? $data['per'] : 3;

			$input = array(
				'u_name' 	=> $tennhanvien,
				'u_user' 	=> $usernv,
				'u_pass' 	=> $pass,
				'u_email' 	=>$email,
				'u_detail' 	=> $mota,
				'u_phone' 	=>$sdt, 
				'u_point' 	=> $point, 
				'u_img' 	=> $hinhanh, 
				'u_per' 	=> $per
			);
			
			$add = $this->user_model->create($input);

			$id = $this->db->insert_id();

			
			if($add) {
				$this->session->set_flashdata('msgsuccess', 'Thêm thành công !');
				echo $id;
			} else {
				$this->session->set_flashdata('msgerror', 'Lỗi vui lòng thử lại sau !');
				echo 'error';
			}
			exit();
		} else {
			return false;
		}
	}

	// Chỉnh sửa user
	function edit() {
		$user_info = $this->session->userdata('login');
		if(!Per_checkAction('user', 'edit', $user_info->u_point)){
			echo "Bạn không có quyền truy cập trang này !";
			exit();
		}
		$this->load->model('user_model');
		$data = $this->input->post('data');

		if(isset($data)) {
			// Validation
			if(isset($data['tennv']) && $data['tennv'] == '') {
				echo "Lỗi: Tên thành viên không được bỏ trống.";
				exit();
			}
			if(isset($data['usernv']) && $data['usernv'] != '') {
				$where = array('u_user' => $data['usernv'], 'u_id !=' => $data['postid']);
				if($this->user_model->check_exists($where)){
					echo "Lỗi: Tên tài khoản đã tồn tại.";
					exit();
				}
			}

			if(!empty($data['sdt'])) {
				if(!is_numeric($data['sdt'])) {
					echo "Lỗi: Số điện thoại phải là số.";
					exit();
				}else{
					$where = array('u_phone' => $data['sdt'], 'u_id !=' => $data['postid']);
					if($this->user_model->check_exists($where)){
						echo "Lỗi: Số điện thoại đã tồn tại.";
						exit();
					}
				}
			}
			if(isset($data['email']) && $data['email'] == '') {
				echo "Lỗi: Email không được bỏ trống.";
				exit();
			}else{
				if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
					echo "Email không hợp lệ";
					exit();
				}else{
					$where = array('u_email' => $data['email'], 'u_id !=' => $data['postid']);
					if($this->user_model->check_exists($where)){
						echo "Lỗi: Email đã tồn tại.";
						exit();
					}
				}
			}
			// End validation
			
			if(isset($_FILES['data']['name']['hinhanh']) && $_FILES['data']['name']['hinhanh'] != ''){
				// File name
				$fileName = $_FILES['data']['name']['hinhanh'];
				// File size
				$fileSize = $_FILES['data']['size']['hinhanh'];
				// File TMP
				$fileTemp = $_FILES['data']['tmp_name']['hinhanh'];
				// File Type
				$fileType = $_FILES['data']['type']['hinhanh'];
				// File Error
				$fileError = $_FILES['data']['error']['hinhanh'];
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
				$moveResult = move_uploaded_file($fileTemp, "uploads/images/user/".$fileName);
				if ($moveResult != true) {
					echo "Lỗi: Không thể upload.";
					exit();
				}
				// Thumbnail
				$this->load->library('Process_images');
				$target_file = "uploads/images/user/".$fileName;
				$thumbnail_file = "uploads/images/user/thumbnail_".$fileName;
				$crop_file = "uploads/images/user/crop_".$fileName;
				$this->process_images->crop_image($target_file, $crop_file, THUMBNAIL_WIDTH, THUMBNAIL_HEIGHT, $ext);
				$this->process_images->ak_img_resize($crop_file, $thumbnail_file, THUMBNAIL_WIDTH, THUMBNAIL_HEIGHT, $ext);
				unlink($crop_file);
				// End thumbnail

				$data['hinhanh'] = $fileName;
			}
			// End Upload Image

			// Point
			
			$point = array();
			if(!empty($data['point'])){
				foreach($data['point'] as $selected){
					$point[] = $selected;
				}
			}
			$point = array_unique($point);
			$data['point'] = 0;
			foreach ($point as $value) {
				$data['point'] += $value;
			}
			
			//Xử lý dữ liệu
			$id = $data['postid'];
			$tennhanvien = $this->db->escape_str($data['tennv']);
			$usernv = $this->db->escape_str($data['usernv']);
			$email = isset($data['email']) ? $this->db->escape_str($data['email']) : '';
			$sdt = isset($data['sdt']) ? $this->db->escape_str($data['sdt']) : '';
			$mota = isset($data['mota']) ? $data['mota'] : '';
			$hinhanh = isset($data['hinhanh']) ? $data['hinhanh'] : '';
			if(in_array($data['postid'], $this->config->item('super_admin'))){
				$point = $this->config->item('per_max_point');
				$per = 1;
			}else{
				$point = isset($data['point']) ? $data['point'] : 1;
				$per = isset($data['per']) ? $data['per'] : 3;
			}

			
			if($hinhanh != '') {
				$input = array(
					'u_name' 	=> $tennhanvien, 
					'u_user' 	=> $usernv, 
					'u_email' 	=>$email, 
					'u_detail' 	=> $mota, 
					'u_phone' 	=>$sdt, 
					'u_point' 	=> $point, 
					'u_per' 	=> $per, 
					'u_img' 	=> $hinhanh
				);
			} else {
				$input = array(
					'u_name' 	=> $tennhanvien,
					'u_user' 	=> $usernv,
					'u_email' 	=>$email,
					'u_detail' 	=> $mota,
					'u_phone' 	=>$sdt,
					'u_per' 	=> $per,
					'u_point' 	=> $point
				);
			}
			
			$edit = $this->user_model->update($id, $input);

			if($edit) {
				$this->session->set_flashdata('msgsuccess', 'Chỉnh sửa thành công !');
				echo $id;
			} else {
				$this->session->set_flashdata('msgerror', 'Lỗi vui lòng thử lại sau !');
				echo 'error';
			}
			exit();
		} else {
			return false;
		}
	}

	function resetpass_user() {
		$user_info = $this->session->userdata('login');
		if(!Per_checkAction('user', 'edit', $user_info->u_point)){
			echo "Bạn không có quyền truy cập trang này !";
			exit();
		}
		$this->load->model('user_model');
		$data = $this->input->post('data');
		if(isset($data)) {
			// Validation
			if(isset($data['pass']) && $data['pass'] == '') {
				echo "Lỗi: Mật khẩu không được bỏ trống.";
				exit();
			}
			// End validation
			
			$id = $data['postid'];
			$pass = isset($data['pass']) ? sha1(md5(sha1(addslashes($data['pass'])))) : '';

			$input = array('u_pass' => $pass);
			
			$update = $this->user_model->update($id, $input);

			if($update) {
				$this->session->set_flashdata('msgsuccess', 'Chỉnh sửa nhân viên thành công !');
				echo $id;
			} else {
				$this->session->set_flashdata('msgerror', 'Lỗi vui lòng thử lại sau !');
				echo 'error';
			}
			exit();
		} else {
			return false;
		}
	}
	// Xóa user
	function delete() {
		$user_info = $this->session->userdata('login');
		if(!Per_checkAction('user', 'delete', $user_info->u_point)){
			echo "Bạn không có quyền truy cập trang này !";
			exit();
		}
		$this->load->model('user_model');
		$data = $this->input->post('delete_post');
		if(isset($data) && is_numeric($data)) {
			$this->user_model->posts_clear_relationships($data);
			$this->user_model->delete_posts($data);
			$this->user_model->delete($data);
			exit();
		} else {
			return false;
		}
	}

	// Xóa nhiều user
	function delete_multi() {
		$user_info = $this->session->userdata('login');
		if(!Per_checkAction('user', 'delete', $user_info->u_point)){
			echo "Bạn không có quyền truy cập trang này !";
			exit();
		}
		$this->load->model('user_model');
		$data = $this->input->post('delete_multi_post');
		if(isset($data) && $data != '') {
			$this->user_model->posts_clear_relationships($data);
			$this->user_model->delete_posts($data);
			$this->user_model->delete($data);
			exit();
		} else {
			return false;
		}
	}

	// Chỉnh sửa user
	function my_profile() {
		$this->load->model('user_model');
		$data = $this->input->post('data');
		if(isset($data)) {
			// Validation
			if(isset($data['name']) && $data['name'] == '') {
				echo "Lỗi: Họ tên không được bỏ trống.";
				exit();
			}
			if(isset($data['user']) && $data['user'] != '') {
				$where = array('u_user' => $data['user'], 'u_id !=' => $data['postid']);
				if($this->user_model->check_exists($where)){
					echo "Lỗi: Tên tài khoản đã tồn tại.";
					exit();
				}
			}

			if(!empty($data['sdt'])) {
				if(!is_numeric($data['sdt'])) {
					echo "Lỗi: Số điện thoại phải là số.";
					exit();
				}else{
					$where = array('u_phone' => $data['sdt'], 'u_id !=' => $data['postid']);
					if($this->user_model->check_exists($where)){
						echo "Lỗi: Số điện thoại đã tồn tại.";
						exit();
					}
				}
			}
			// End validation
			
			//Xử lý dữ liệu
			$id = $data['postid'];
			$tennhanvien = $this->db->escape_str($data['name']);
			$usernv = $this->db->escape_str($data['user']);
			//$email = isset($data['email']) ? $this->db->escape_str($data['email']) : '';
			$sdt = isset($data['sdt']) ? $this->db->escape_str($data['sdt']) : '';
			$link = isset($data['link']) ? $data['link'] : '';
			$mota = isset($data['mota']) ? $data['mota'] : '';
			
			$input = array(
				'u_name' 	=> $tennhanvien,
				'u_user' 	=> $usernv,
				'u_link' 	=>$link,
				'u_detail' 	=> $mota,
				'u_phone' 	=>$sdt
			);
			
			$update = $this->user_model->update($id, $input);
			if($update) {
				$where = array('u_id' => $id);
				//Lấy thông tin thành viên
	            $user = $this->user_model->get_info_rule($where);	             
	            //Lưu thông tin thành viên vào session
	           	$this->session->set_userdata('login', $user);

				$this->session->set_flashdata('msgsuccess', 'Cập nhật thành công !');
				echo $id;
			} else {
				$this->session->set_flashdata('msgerror', 'Lỗi vui lòng thử lại sau !');
				echo 'error';
			}
			exit();
		} else {
			return false;
		}
	}
	function my_profile_img(){
		$this->load->model('user_model');
		$data = $this->input->post('data');
		if(isset($data)) {
			// Upload Image
			if(isset($_FILES['data']['name']['hinhanh']) && $_FILES['data']['name']['hinhanh'] != ''){
				// File name
				$fileName = $_FILES['data']['name']['hinhanh'];
				// File size
				$fileSize = $_FILES['data']['size']['hinhanh'];
				// File TMP
				$fileTemp = $_FILES['data']['tmp_name']['hinhanh'];
				// File Type
				$fileType = $_FILES['data']['type']['hinhanh'];
				// File Error
				$fileError = $_FILES['data']['error']['hinhanh'];
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
				$moveResult = move_uploaded_file($fileTemp, "uploads/images/user/".$fileName);
				if ($moveResult != true) {
					echo "Lỗi: Không thể upload.";
					exit();
				}
				// Thumbnail
				$this->load->library('Process_images');
				$target_file = "uploads/images/user/".$fileName;
				$thumbnail_file = "uploads/images/user/thumbnail_".$fileName;
				$this->process_images->ak_img_resize($target_file, $thumbnail_file, THUMBNAIL_WIDTH, THUMBNAIL_HEIGHT, $ext);
				// End thumbnail

				$data['hinhanh'] = $fileName;
			}else{
				echo "Lỗi: Chưa chọn hình ảnh !";
				exit();
			}
			$input = array('u_img' => $data['hinhanh']);			
			$update = $this->user_model->update($data['postid'], $input);

			if($update) {
				$where = array('u_id' => $data['postid']);
				//Lấy thông tin thành viên
	            $user = $this->user_model->get_info_rule($where);	             
	            //Lưu thông tin thành viên vào session
	           	$this->session->set_userdata('login', $user);

				$this->session->set_flashdata('msgsuccess', 'Cập nhật thành công !');
				echo $data['postid'];
			} else {
				$this->session->set_flashdata('msgerror', 'Lỗi vui lòng thử lại sau !');
				echo 'error';
			}
			exit();
		}else {
			return false;
		}
	}
	function my_profile_pass() {
		$this->load->model('user_model');
		$data = $this->input->post('data');
		if(isset($data)) {
			// Validation
			if(isset($data['oldpass']) && $data['oldpass'] == '') {
				echo "Lỗi: Mật khẩu cũ không được bỏ trống.";
				exit();
			}
			if(isset($data['newpass']) && $data['newpass'] == '') {
				echo "Lỗi: Mật khẩu mới không được bỏ trống.";
				exit();
			}
			if(!isset($data['renewpass']) || $data['newpass'] != $data['renewpass']) {
				echo "Lỗi: Nhập lại mật khẩu chưa đúng.";
				exit();
			}
			$data['pass'] = $data['newpass'];
			$user_info = $this->session->userdata('login');
			$data['postid'] = $user_info->u_id;
			
			$oldpass = $this->user_model->get_info($data['postid'], 'u_pass');
			$newpass = sha1(md5(sha1(addslashes($data['oldpass']))));
			if($oldpass->u_pass != $newpass){
				echo "Lỗi: Mật khẩu cũ chưa đúng.";
				exit();
			}

			//End validation
			//
			$input = array('u_pass' => sha1(md5(sha1(addslashes($data['pass'])))));			
			$update = $this->user_model->update($data['postid'], $input);
			
			if($update) {
				$this->session->set_flashdata('msgsuccess', 'Cập nhật thành công !');
				echo $data['postid'];
			} else {
				$this->session->set_flashdata('msgerror', 'Lỗi vui lòng thử lại sau !');
				echo 'error';
			}
			exit();
		} else {
			return false;
		}
    }
    
}