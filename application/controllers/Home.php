<?php
	/**
	* 
	*/
	class Home extends My_controller {
		
		function __construct(){
			parent::__construct();
			$this->load->model('posts_model');
			$this->load->model('post_meta_model');
			$user_info = $this->session->userdata('login');
			if(!Per_checkController('posts', $user_info->u_point)){
				redirect(base_url().ADMIN.'fail/permission');
			}
		}

		function index(){
			$user_info = $this->session->userdata('login');
			if(Per_checkAction('posts', 'view', $user_info->u_point)){
				$data = array();
				$data['layout'] = true;
				$data['active'] = 'posts';
				$data['subactive'] = 'posts_all';
				$data['title'] = "Admin &raquo Tất cả tin tức";
				$input['order'] = array('p_id','ASC');
				if((int)$user_info->u_per > 1){
					$input['where'] = array('p_type' => 1);
				}

				if(isset($_GET['start'])){
					$data['start'] = $_GET['start'];
				}else{
					$data['start'] = date('m-Y', strtotime('-1 month'));
				}

				if(isset($_GET['end'])){
					$data['end'] = $_GET['end'];
				}else{
					$data['end'] = date('m-Y', strtotime('-1 month'));
				}

				$data['posts'] = $this->posts_model->get_list_relationships($input, $user_info->u_id, $user_info->u_per);

				$this->load->model('network_model');
				$input2['order'] = array('n_order','ASC');
				$data['network'] = $this->network_model->get_list($input2);

			}else{
				$data = array('layout' => true, 'active' => 'error', 'subactive' => 'permission');
			}
			$this->load->view('backend/index', $data);
		}

		function myprofile(){
			$data = array();
			$data['layout'] = true;
			$data['active'] = 'home';
			$data['subactive'] = 'myprofile';
			$data['title'] = 'Admin &raquo My Profile';
			$data['posts'] = $this->session->userdata('login');
			$this->load->view('backend/index', $data);
		}
	}
?>