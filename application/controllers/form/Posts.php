<?php

class Posts extends MY_Controller{

	function __construct() {
		parent::__construct();
		$this->load->model('posts_model');
		$this->load->model('post_meta_model');
	}
	
	//Thêm
	function add() {
		$result = array();
		$result['message'] = '';
		$result['status'] = false;
		$user_info = $this->session->userdata('login');
		if(!Per_checkAction('posts', 'add', $user_info->u_point)){
			$result['message'] = "Error: Bạn không có quyền truy cập trang này !";
			echo json_encode($result);
			exit();
		}
		
		$data = $this->input->post('data', false);
		if(isset($data['time']) && $data['time'] == '') {
			$result['message'] = "Lỗi: Chưa chọn tháng !";
			echo json_encode($result);
			exit();
		}

		$time = date('Y-m-d', strtotime('01-'.$data['time']));

		if ( isset($_FILES["fileupload"])) {

			$fileName = $_FILES["fileupload"]["name"];
			$fileTemp = $_FILES['fileupload']['tmp_name'];

			if (!preg_match("/.(csv|xls|xlsx)$/i", $fileName) ) {
				$result['message'] = "Lỗi: Loại file cho phép (.csv,.xls,.xlsx).";
				unlink($fileTemp);
				echo json_encode($result);
				exit();
			}
			//if there was an error uploading the file
			if ($_FILES["fileupload"]["error"] > 0) {
				$result['message'] = "Return Code: " . $_FILES["fileupload"]["error"];
				echo json_encode($result);
				exit();
			}
			else {	  
				//Store file in directory "upload" with the name of "uploaded_file.txt"
				$storagename = time()."_".$_FILES["fileupload"]["name"];
				$moveResult = move_uploaded_file($_FILES["fileupload"]["tmp_name"], "uploads/files/import/" . $storagename);
				if ($moveResult != true) {
					$result['message'] = "Lỗi: Không thể upload.";
					echo json_encode($result);
					exit();
				}else{
					$result['message'] = "Upload file thành công !";
					$result['status'] = true;
					$list = array();
					$file = "uploads/files/import/".$storagename;

					$this->load->library('excel');
					$objPHPExcel = PHPExcel_IOFactory::load($file);
					$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

					foreach ($cell_collection as $cell) {
						$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
						$row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
						$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
					
						//The header will/should be in row 1 only. of course, this can be modified to suit your need.
						if ($row == 1) {
							$header[] = $data_value;
						} else {
							$arr_data[($row -1)][] = $data_value;
						}
					}

					$array_new = array();
					$array_old = array();

					foreach ($arr_data as $key => $value) {
						if($this->posts_model->check_exists(array("p_channel" => $value[1]))){
							$id = $this->posts_model->get_info_rule(array("p_channel" => $value[1]), 'p_id')->p_id;							
							$arr_data[$key][4] = $this->posts_model->get_info_rule(array("p_channel" => $value[1]), 'p_type')->p_type;
							$arr_data[$key][3] = (int)$id;

							// $input = array(
							// 	'p_title' => $value[0]
							// );	
							// $add = $this->posts_model->update($id, $input);

							$array_old[] = $arr_data[$key];
						}else{
							$input = array(
								'p_user_id' => $user_info->u_id,
								'p_title' => $value[0],
								'p_channel' => $value[1]
							);			
							$add = $this->posts_model->create($input);
							$id = $this->db->insert_id();
							$arr_data[$key][4] = 1;
							$arr_data[$key][3] = (int)$id;

							$array_new[] = $arr_data[$key];
						}

						if($this->post_meta_model->check_exists(array("pm_p_id" => $id, 'pm_time' => $time))){							
							$input_meta = array(
								'pm_value' => $value[2]
							);			
							$add = $this->post_meta_model->update_rule(array("pm_p_id" => $id, 'pm_time' => $time), $input_meta);
						}else{
							$input_meta = array(
								'pm_p_id' => $id,
								'pm_time' => $time,
								'pm_value' => $value[2]
							);	
							$add = $this->post_meta_model->create($input_meta);
						}
						
					}

					//send the data in an array format
					$result['data']['header'] = $header;
					$result['data']['values'] = array_merge($array_new,$array_old);

					echo json_encode($result);
					exit();
				}
			}
		}else{
			$result['message'] = "Lỗi: Chưa có file !";
			echo json_encode($result);
			exit();
		}

	}

	//Thêm chi tiết
	function add_detail() {
		$result = array();
		$result['message'] = '';
		$result['status'] = false;
		$user_info = $this->session->userdata('login');
		if(!Per_checkAction('posts', 'add', $user_info->u_point)){
			$result['message'] = "Error: Bạn không có quyền truy cập trang này !";
			echo json_encode($result);
			exit();
		}
		
		$data = $this->input->post('data', false);
		if(isset($data['time']) && $data['time'] == '') {
			$result['message'] = "Lỗi: Chưa chọn tháng !";
			echo json_encode($result);
			exit();
		}

		$time = date('Y-m-d', strtotime('01-'.$data['time']));

		if ( isset($_FILES["fileupload"]) && isset($_FILES["fileupload2"])) {

			$fileName = $_FILES["fileupload"]["name"];
			$fileTemp = $_FILES['fileupload']['tmp_name'];

			$fileName2 = $_FILES["fileupload2"]["name"];
			$fileTemp2 = $_FILES['fileupload2']['tmp_name'];

			if (!preg_match("/.(csv|xls|xlsx)$/i", $fileName) ) {
				$result['message'] = "Lỗi: Loại file cho phép (.csv,.xls,.xlsx).";
				unlink($fileTemp);
				echo json_encode($result);
				exit();
			}

			if (!preg_match("/.(csv|xls|xlsx)$/i", $fileName2) ) {
				$result['message'] = "Lỗi: Loại file cho phép (.csv,.xls,.xlsx).";
				unlink($fileTemp2);
				echo json_encode($result);
				exit();
			}

			//if there was an error uploading the file
			if ($_FILES["fileupload"]["error"] > 0) {
				$result['message'] = "Return Code: " . $_FILES["fileupload"]["error"];
				echo json_encode($result);
				exit();
			}elseif($_FILES["fileupload2"]["error"] > 0){
				$result['message'] = "Return Code: " . $_FILES["fileupload2"]["error"];
				echo json_encode($result);
				exit();
			} else {	  
				//Store file in directory "upload" with the name of "uploaded_file.txt"
				$storagename = time()."_".$_FILES["fileupload"]["name"];
				$moveResult = move_uploaded_file($_FILES["fileupload"]["tmp_name"], "uploads/files/import/" . $storagename);

				$storagename2 = time()."_".$_FILES["fileupload2"]["name"];
				$moveResult2 = move_uploaded_file($_FILES["fileupload2"]["tmp_name"], "uploads/files/import/" . $storagename2);

				if ($moveResult != true) {
					$result['message'] = "Lỗi: Không thể upload file Summary";
					echo json_encode($result);
					exit();
				}elseif($moveResult2 != true){
					$result['message'] = "Lỗi: Không thể upload file Red";
					echo json_encode($result);
					exit();
				}else{
					$result['message'] = "Upload file thành công !";
					$result['status'] = true;
					$list = array();
					$file = "uploads/files/import/".$storagename;
					$file2 = "uploads/files/import/".$storagename2;

					$this->load->library('excel');

					$objPHPExcel = PHPExcel_IOFactory::load($file);
					$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

					foreach ($cell_collection as $cell) {
						$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
						$row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
						$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
					
						//The header will/should be in row 1 only. of course, this can be modified to suit your need.
						if ($row == 1) {
							$header[] = $data_value;
						} else {
							$arr_data[($row -1)][] = $data_value;
						}
					}

					$objPHPExcel = PHPExcel_IOFactory::load($file2);
					$cell_collection2 = $objPHPExcel->getActiveSheet()->getCellCollection();

					foreach ($cell_collection2 as $cell2) {
						//$column2 = $objPHPExcel->getActiveSheet()->getCell($cell2)->getColumn();
						$row2 = $objPHPExcel->getActiveSheet()->getCell($cell2)->getRow();
						$data_value2 = $objPHPExcel->getActiveSheet()->getCell($cell2)->getValue();
					
						//The header will/should be in row 1 only. of course, this can be modified to suit your need.
						if ($row2 == 1) {
							$header2[] = $data_value2;
						} else {
							$arr_data2[($row2 - 2)][] = $data_value2;
						}
					}
					

					$result_data = array();


					foreach ($arr_data as $key => $value) {
						$arr_key = substr($value[0], 2);
						if( isset($value[2]) &&  $value[2] != 0){
							if(isset( $result_data[ $arr_key ])){
								$result_data[$arr_key][1] += $value[2];
							}else{							
								$result_data[$arr_key] = array($value[1], $value[2]);
							}
						}
					}

					foreach ($arr_data2 as $key => $value) {
						$arr_key = $value[1];
						if( isset($value[2]) &&  $value[2] != 0){
							if(isset( $result_data[ $arr_key ])){
								$result_data[$arr_key][1] += $value[2];
							}else{							
								$result_data[$arr_key] = array($value[0], $value[2]);
							}
						}					
					}

					$results = array();

					foreach ($result_data as $item => $items) {
						$arr_item = array();
						$arr_item[0] = $items[0];
						$arr_item[1] = $item;
						$arr_item[2] = number_format($items[1], 3);
						if($this->posts_model->check_exists(array("p_channel" => $item))){
							$id = $this->posts_model->get_info_rule(array("p_channel" => $item), 'p_id')->p_id;							
							$arr_item[4] = $this->posts_model->get_info_rule(array("p_channel" => $item), 'p_type')->p_type;
							$arr_item[3] = (int)$id;

							// $input = array(
							// 	'p_title' => $value[0]
							// );	
							// $add = $this->posts_model->update($id, $input);
						}else{
							$input = array(
								'p_user_id' => $user_info->u_id,
								'p_title' => $items[0],
								'p_channel' => $item
							);			
							$add = $this->posts_model->create($input);
							$id = $this->db->insert_id();
							$arr_item[4] = 1;
							$arr_item[3] = (int)$id;
						}

						if($this->post_meta_model->check_exists(array("pm_p_id" => $id, 'pm_time' => $time))){							
							$input_meta = array(
								'pm_value' => $items[1]
							);			
							$add = $this->post_meta_model->update_rule(array("pm_p_id" => $id, 'pm_time' => $time), $input_meta);
						}else{
							$input_meta = array(
								'pm_p_id' => $id,
								'pm_time' => $time,
								'pm_value' => $items[1]
							);	
							$add = $this->post_meta_model->create($input_meta);
						}

						$results[] = $arr_item;
					}

					//send the data in an array format
					//$result['data']['header'] = $header;
					$result['data']['values'] = $results;

					echo json_encode($result);
					exit();
				}
			}
		}else{
			$result['message'] = "Lỗi: Chưa có file !";
			echo json_encode($result);
			exit();
		}

	}

	// Chỉnh sửa 
	function edit() {
		$data = $this->input->post('data', false);
		
		$user_info = $this->session->userdata('login');

		if(!Per_checkAction('posts', 'edit', $user_info->u_point)){
			echo "Bạn không có quyền truy cập trang này !";
			exit();
		}
		
		if(isset($data)) {
			// Validation
			if(isset($data['title']) && $data['title'] == '') {
				echo "Lỗi: Tiêu đề không được bỏ trống.";
				exit();
			}

			if(isset($data['rate']) && $data['rate'] == '') {
				echo "Lỗi: Rate không được bỏ trống.";
				exit();
			}
			// End validation

			//Xử lý dữ liệu
			$id = $data['postid'];

			$title = $this->db->escape_str($data['title']);
			$rate = $data['rate'];
			$network = $data['network'];
			$zone = $data['zone'];
			$zone = implode(",",$zone);
			$cat = $data['cat'];
			$cat = implode(",",$cat);
			$detail = isset($data['detail']) ? $data['detail'] : '';
			
			$input = array(
				'p_title' 			=> $title,
				'p_rate' 			=> $rate,
				'p_network'			=> $network,
				'p_detail'			=> $detail,
				'p_zone'			=> $zone,
				'p_cat'				=> $cat,
			);
			
			$edit = $this->posts_model->update($id, $input);

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

	// Chỉnh sửa 
	function add_channel() {
		$data = $this->input->post('data', false);
		
		$user_info = $this->session->userdata('login');

		if(!Per_checkAction('posts', 'add', $user_info->u_point)){
			echo "Bạn không có quyền truy cập trang này !";
			exit();
		}
		
		if(isset($data)) {
			// Validation
			if(isset($data['title']) && $data['title'] == '') {
				echo "Lỗi: Tiêu đề không được bỏ trống.";
				exit();
			}

			if(isset($data['channel']) && $data['channel'] == '') {
				echo "Lỗi: Channel ID không được bỏ trống.";
				exit();
			}

			if(isset($data['rate']) && $data['rate'] == '') {
				echo "Lỗi: Rate không được bỏ trống.";
				exit();
			}
			// End validation

			//Xử lý dữ liệu
			$title = $this->db->escape_str($data['title']);
			$rate = $data['rate'];
			$channel = $data['channel'];
			$network = $data['network'];
			$detail = isset($data['detail']) ? $data['detail'] : '';
			
			$input = array(
				'p_title' 			=> $title,
				'p_rate' 			=> $rate,
				'p_channel' 		=> $channel,
				'p_network'			=> $network,
				'p_detail'			=> $detail
			);
			
			$add = $this->posts_model->create($input);

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

	//Xóa
	function delete() {
		$data = $this->input->post('delete_post');

		$user_info = $this->session->userdata('login');

		if(!Per_checkAction('posts', 'delete', $user_info->u_point)){
			echo "Bạn không có quyền truy cập trang này !";
			exit();
		}

		if((int)$user_info->u_per > 1){
			echo "Error: Quyền Super Admin trở lên mới thực hiện được thao tác này !";
			exit();
		}
		
		if(isset($data) && is_numeric($data)) {			
			$this->posts_model->posts_clear_relationships($data);
			$this->posts_model->clear_post_meta($data);
			$this->posts_model->delete_posts($data);
			$this->posts_model->delete($data);
			exit();
		} else {
			return false;
		}
	}

	

	//Xóa nhiều mục
	function delete_multi() {
		$user_info = $this->session->userdata('login');
		if(!Per_checkAction('posts', 'delete', $user_info->u_point)){
			echo "Bạn không có quyền truy cập trang này !";
			exit();
		}

		if((int)$user_info->u_per > 1){
			echo "Error: Quyền Super Admin trở lên mới thực hiện được thao tác này !";
			exit();
		}

		$data = $this->input->post('delete_multi_post');
		if(isset($data) && $data != '') {
			$array = explode(',',$data);
			foreach ($array as $key => $value) {
				$this->posts_model->posts_clear_relationships($value);
				$this->posts_model->clear_post_meta($value);
				$this->posts_model->delete_posts($value);	
				$this->posts_model->delete($value);
								
			}
		} else {
			return false;
		}
	}

	//Thay đổi trạng thái
	function type(){
		$result = array();
		$user_info = $this->session->userdata('login');
		if(!Per_checkAction('posts', 'edit', $user_info->u_point)){
			echo "Bạn không có quyền truy cập trang này !";
			exit();
		}

		$id = $this->input->post('id');
		$get = $this->posts_model->get_info_rule(array("p_id" => $id), 'p_type')->p_type;
		$type = ($get == 1)?2:1;
		$add = $this->posts_model->update($id, array("p_type" => $type));
		$result['success'] = true;
		echo json_encode($result);
	}

	function add_meta(){
		$user_info = $this->session->userdata('login');
		if(!Per_checkAction('posts', 'add', $user_info->u_point)){
			$result['message'] = "Error: Bạn không có quyền truy cập trang này !";
			echo json_encode($result);
			exit();
		}

		if((int)$user_info->u_per > 1){
			$result['message'] = "Error: Quyền Super Admin trở lên mới thực hiện được thao tác này !";
			echo json_encode($result);
			exit();
		}

		$result = array();
		$id = $this->input->post('id');
		$time = $this->input->post('time');
		$value = $this->input->post('value');
		$time = date('Y-m-d', strtotime('01-'.$time));

		if($this->post_meta_model->check_exists(array("pm_p_id" => $id, 'pm_time' => $time))){	
			$input_meta = array(
				'pm_value' => $value
			);			
			$add = $this->post_meta_model->update_rule(array("pm_p_id" => $id, 'pm_time' => $time), $input_meta);
			if($add){
				$result['success'] = true;
				$result['message'] = 'Cập nhật lại dữ liệu cũ thành công !';
			}else{
				$result['error'] = true;
			}
		}else{
			$input_meta = array(
				'pm_p_id' => $id,
				'pm_time' => $time,
				'pm_value' => $value
			);	
			$add = $this->post_meta_model->create($input_meta);
			if($add){
				$result['success'] = true;
				$result['message'] ='Thêm mới thành công !';
			}else{
				$result['error'] = true;
			}
		}

		echo json_encode($result);
	}

	function delete_meta() {
		$data = $this->input->post('delete_post');

		$user_info = $this->session->userdata('login');

		if(!Per_checkAction('posts', 'delete', $user_info->u_point)){
			echo "Bạn không có quyền truy cập trang này !";
			exit();
		}

		if((int)$user_info->u_per > 1){
			$result['message'] = "Error: Quyền Super Admin trở lên mới thực hiện được thao tác này !";
			echo json_encode($result);
			exit();
		}
		
		if(isset($data) && is_numeric($data)) {
			$this->post_meta_model->delete($data);
			exit();
		} else {
			return false;
		}
	}
	
	function add_relationships(){
		$this->load->model('relationships_model');
		$user_info = $this->session->userdata('login');
		if(!Per_checkAction('posts', 'add', $user_info->u_point)){
			$result['message'] = "Error: Bạn không có quyền truy cập trang này !";
			echo json_encode($result);
			exit();
		}

		if((int)$user_info->u_per > 3){
			$result['message'] = "Error: Quyền Admin trở lên mới thực hiện được thao tác này !";
			echo json_encode($result);
			exit();
		}

		$result = array();
		$id = $this->input->post('id');
		$value = $this->input->post('value');

		if($this->relationships_model->check_exists(array("object_id" => $id, 'target_id' => $value))){
			$result['error'] = true;
			$result['message'] = 'User đã tồn tại !';
		}else{
			$input_meta = array(
				'object_id' => $id,
				'target_id' => $value
			);	
			$add = $this->relationships_model->create($input_meta);
			if($add){
				$result['success'] = true;
				$result['message'] ='Thêm mới thành công !';
			}else{
				$result['error'] = true;
			}
		}

		echo json_encode($result);
	}

	function delete_relationships(){
		$this->load->model('relationships_model');
		$user_info = $this->session->userdata('login');
		if(!Per_checkAction('posts', 'delete', $user_info->u_point)){
			$result['message'] = "Error: Bạn không có quyền truy cập trang này !";
			echo json_encode($result);
			exit();
		}

		if((int)$user_info->u_per > 3){
			$result['message'] = "Error: Quyền Admin trở lên mới thực hiện được thao tác này !";
			echo json_encode($result);
			exit();
		}


		$result = array();
		$id = $this->input->post('id');
		$value = $this->input->post('value');

		$delete = $this->relationships_model->del_rule(array('object_id' => $id, 'target_id' => $value));

		if($delete){
			$result['success'] = true;
			$result['message'] ='Xóa thành công !';
		}else{
			$result['error'] = true;
			$result['message'] = 'Lỗi vui lòng thử lại !';
		}

		echo json_encode($result);
	}
	
}