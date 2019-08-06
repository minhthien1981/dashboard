<?php
	/**
	* Fail
	*/
	class Fail extends My_controller {
		
		function __construct(){
			parent::__construct();
		}

		function index(){
			$data = array('layout' => true, 'active' => 'error', 'subactive' => '404');
			$this->load->view('backend/index', $data);
		}

		function permission(){
			$data = array('layout' => true, 'active' => 'error', 'subactive' => 'permission');
			$this->load->view('backend/index', $data);
		}


	}