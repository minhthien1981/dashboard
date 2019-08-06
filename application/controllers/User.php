<?php
	/**
	* 
	*/
	class User extends My_controller {
		
		function __construct(){
			parent::__construct();
			$this->load->model('user_model');
			$user_info = $this->session->userdata('login');
			if(!Per_checkController('user', $user_info->u_point)){
				$data = array('layout' => true, 'active' => 'error', 'subactive' => 'permission');
				redirect(base_url().ADMIN.'fail/permission');
			}
		}

		function index(){
			redirect(base_url().ADMIN);
		}

		function user_all(){
			$user_info = $this->session->userdata('login');
			if(Per_checkAction('user', 'view', $user_info->u_point)){
				$data = array();
				$data['layout'] = true;
				$data['active'] = 'user';
				$data['subactive'] = 'user_all';
				$data['title'] = "Admin &raquo Tất cả user";
				//$input['limit'] = array('5', 0);
				$input['order'] = array('u_id','ASC');
				$data['posts'] = $this->user_model->get_list($input);
			}else{
				$data = array('layout' => true, 'active' => 'error', 'subactive' => 'permission');
			}
			$this->load->view('backend/index', $data);
		}

		function user_add(){
			$user_info = $this->session->userdata('login');
			if(Per_checkAction('user', 'add', $user_info->u_point)){
				$data = array();
				$data['layout'] = true;
				$data['active'] = 'user';
				$data['subactive'] = 'user_add';
				$data['title'] = "Admin &raquo Thêm user";
				$input['order'] = array('taxonomy_order','ASC');
			}else{
				$data = array('layout' => true, 'active' => 'error', 'subactive' => 'permission');
			}
			$this->load->view('backend/index', $data);
		}

		function user_edit(){
			$user_info = $this->session->userdata('login');
			if(Per_checkAction('user', 'edit', $user_info->u_point)){
				$data = array();
				$data['layout'] = true;
				$data['active'] = 'user';
				$data['subactive'] = 'user_edit';
				$data['title'] = "Admin &raquo Chỉnh sửa user";
				if(ADMIN != ''){ $id = $this->uri->segment(4);}else{$id = $this->uri->segment(3);}
				if(is_numeric($id) && $id != ''){
					$data['posts'] = $this->user_model->get_info($id);
					if(!$data['posts']){
						$data = array('layout' => true, 'active' => 'error', 'subactive' => '404');
						$this->load->view('backend/index', $data);
					}
				}else{
					redirect(base_url().ADMIN.'user/user_all');
				}
			}else{
				$data = array('layout' => true, 'active' => 'error', 'subactive' => 'permission');
			}
			$this->load->view('backend/index', $data);
		}
	}
?>