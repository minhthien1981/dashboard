<?php
	/**
	* Setting
	*/
	class Setting extends My_controller {
		
		function __construct(){
			parent::__construct();
		}

		function index(){
			redirect(base_url().ADMIN);
		}

		function general(){
			$data = array();
			$data['layout'] = true;
			$data['active'] = 'setting';
			$data['subactive'] = 'general';
			$data['title'] = 'Admin &raquo General Setting';
			//$data['posts'] = $this->my_model->get_options_value('site_title');
			$this->load->view('backend/index', $data);
		}

		function logo(){
			$data = array();
			$data['layout'] = true;
			$data['active'] = 'setting';
			$data['subactive'] = 'logo';
			$data['title'] = 'Admin &raquo Logo Setting';
			$this->load->view('backend/index', $data);
		}

		function network(){
			$data = array();
			$data['layout'] = true;
			$data['active'] = 'setting';
			$data['subactive'] = 'network';
			$data['title'] = 'Admin &raquo Network Setting';
			$this->load->model('network_model');
			$input['order'] = array('n_order','ASC');
			$data['posts'] = $this->network_model->get_list($input);
			$this->load->view('backend/index', $data);
		}
		
	}
?>