<?php
	/**
	 * main
	 */
	class Main extends MY_Controller{
		
		function __construct(){
			parent::__construct();
		}

		function Contact(){
			$this->load->model('contact_model');
			$id = $this->input->post('id');
			$data = $this->contact_model->get_info($id);
			echo '<h3 class="w-100 lee-title">'.$data->c_title.'</h3>';
			echo '<p class="card-text mb-auto">'.$data->c_content.'</p>';
		}
	}