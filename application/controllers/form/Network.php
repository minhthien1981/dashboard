<?php

class Network extends MY_Controller{

	function __construct() {
		parent::__construct();
		$this->load->model('posts_model');
		$this->load->model('post_meta_model');
		$user_info = $this->session->userdata('login');
		if(!Per_checkController('network', $user_info->u_point)){
			echo "Error Premission";
			exit();
		}
	}
	
	
	// Chỉnh sửa 
	function edit() {
		$data = $this->input->post('data', false);
		
		

		if(isset($data)) {
			// Validation
			if(isset($data['rate']) && $data['rate'] == '') {
				echo "Lỗi: Rate không được bỏ trống.";
				exit();
			}

			if(isset($data['rate_network']) && $data['rate_network'] == '') {
				echo "Lỗi: Rate không được bỏ trống.";
				exit();
			}
			// End validation

			//Xử lý dữ liệu
			$id = $data['postid'];

			$rate_network = $data['rate_network'];
			$rate 		  = $data['rate'];
			
			$input = array(
				'p_network_rate' 	=> $rate_network,
				'p_rate' 	=> $rate
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

}