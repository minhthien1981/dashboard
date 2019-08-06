<?php
	/**
	 * 
	 */
	class Ckfinder extends MY_Controller
	{
		
		function __construct() {
			parent::__construct();
		}

		public function connector(){
			
			$user_info = $this->session->userdata('login');
			$ckfinder_can_use = $user_info ? true : false;				

			define("CKFINDER_CAN_USE", $ckfinder_can_use);
			if($user_info->u_per == '1'){
				define("CKFINDER_FODER_UPLOAD", base_url().'uploads/');
			}else{
				define("CKFINDER_FODER_UPLOAD", base_url().'uploads/files/'.$user_info->u_id.'/');
			}

			require_once "./public/documents/ckfinder/core/connector/php/connector.php";
		}

		public function html(){
			
			$this->load->view('backend/ckfinder');
		}


	}