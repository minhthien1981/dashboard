<?php 
	class My_controller extends CI_Controller{

		function __construct(){
			parent::__construct();
			$this->load->model('main_model');
			$this->config->load('permission');
			$this->if_login();
			$this->if_per();
			$this->Per_check();
		}

		private function if_login(){
			//Lấy giá trị Controler
			if(ADMIN != ''){
				$controller = $this->uri->segment(1);
				$controller = strtolower($controller);
				$controller2 = $this->uri->segment(2);
				$controller2 = strtolower($controller2);
				$user_info = $this->session->userdata('login');				
				if($controller != 'cronjob' && $controller != 'api'){
					if(!$user_info && $controller == str_replace('/', '', ADMIN) && $controller2!= 'login'){
						redirect(base_url().ADMIN.'login');
					}
				}
			}else{
				$controller = $this->uri->segment(1);
				$controller = strtolower($controller);
				$user_info = $this->session->userdata('login');

				if($controller != 'cronjob' && $controller != 'api'){
					if(!$user_info && $controller!= 'login'){
						redirect(base_url().'login');
					}
				}
				
			}
			
		}

		private function if_per(){
			$user_info = $this->session->userdata('login');
			if($user_info){
				$this->load->model('user_model');
				$user = $this->user_model->get_info($user_info->u_id);
				if(in_array($user->u_id, $this->config->item('super_admin'))){
					$user->u_point =  $this->config->item('per_max_point');
					$user->u_per =  '1';
				}
				$this->session->set_userdata('login', $user);
			}
						
		}

		function category_select($data, $parent = 0, $level = '-1', $selected = '0') {
			$html = '';
			$menu_tmp = array();
			foreach ($data as $key => $item) {
				if ($item->taxonomy_parent_id == $parent) {
					$menu_tmp[] = $item;
					unset($data[$key]);
				}
			}
			if($menu_tmp) {
				$level++;
				foreach ($menu_tmp as $key => $item) {
					if($item->taxonomy_id == $selected){
						$html .= '<option selected value="'.$item->taxonomy_id.'">'.str_repeat("— ", $level).$item->taxonomy_name.'</option>';
					}else{
						$html .= '<option value="'.$item->taxonomy_id.'">'.str_repeat("— ", $level).$item->taxonomy_name.'</option>';
					}
					$html .= $this->category_select($data, $item->taxonomy_id, $level);
				}

			}
			return $html;
		}

		function category_input($data, $parent = 0, $level = '-1') {
			$html = '';
			$menu_tmp = array();
			foreach ($data as $key => $item) {
				if ($item->taxonomy_parent_id == $parent) {
					$menu_tmp[] = $item;
					unset($data[$key]);
				}
			}
			if($menu_tmp) {
				$level++;
				switch ($level) {
					case 1:
						$color = 'checkbox-success';
						break;
					case 2:
						$color = 'checkbox-primary';
						break;
					default:
						$color = 'checkbox-info';
						break;
				}
				foreach ($menu_tmp as $key => $item) {
					$html .='<li class="checkbox '.$color.'"><i>'.str_repeat(" — ", $level).'</i>';
					$html .='<input name="data[danhmuc][]" value="'.$item->taxonomy_id.'" type="checkbox" id="checkbox'.$item->taxonomy_id.'"/>';
					$html .='<label for="checkbox'.$item->taxonomy_id.'">'.$item->taxonomy_name.'</label>';
					$html .= $this->category_input($data, $item->taxonomy_id, $level);
					$html .='</li>';
				}

			}
			return $html;
		}

		function category_input_radio($data, $parent = 0, $level = '-1') {
			$html = '';
			$menu_tmp = array();
			foreach ($data as $key => $item) {
				if ($item->taxonomy_parent_id == $parent) {
					$menu_tmp[] = $item;
					unset($data[$key]);
				}
			}
			if($menu_tmp) {
				$level++;
				switch ($level) {
					case 1:
						$color = 'radio-success';
						break;
					case 2:
						$color = 'radio-primary';
						break;
					default:
						$color = 'radio-info';
						break;
				}
				foreach ($menu_tmp as $key => $item) {			
						
					$html .='<li><i>'.str_repeat(" —— ", $level).'</i>';
					$html .='<div class="radio '.$color.'">';
						$html .='<input type="radio" data-name="'.$item->taxonomy_name.'" value="'.$item->taxonomy_id.'" id="radio'.$item->taxonomy_id.'" name="danhmuc" />';
						$html .='<label for="radio'.$item->taxonomy_id.'">'.$item->taxonomy_name.'</label></div>';
					$html .= $this->category_input_radio($data, $item->taxonomy_id, $level);
					$html .='</li>';
				}

			}
			return $html;
		}

		function category_relationships($data, $relationship, $parent = 0, $level = '-1') {
			$html = '';
			$menu_tmp = array();
			foreach ($data as $key => $item) {
				if ($item->taxonomy_parent_id == $parent) {
					$menu_tmp[] = $item;
					unset($data[$key]);
				}
			}
			if($menu_tmp) {
				$level++;
				switch ($level) {
					case 1:
						$color = 'checkbox-success';
						break;
					case 2:
						$color = 'checkbox-primary';
						break;
					default:
						$color = 'checkbox-info';
						break;
				}
				foreach ($menu_tmp as $key => $item) {					

					$html .='<li class="checkbox '.$color.'"><i>'.str_repeat(" — ", $level).'</i>';
					$html .='<input name="data[danhmuc][]" value="'.$item->taxonomy_id.'" type="checkbox"';
						foreach($relationship as $k => $v) {
							if($v->target_id == $item->taxonomy_id) {
								$html .= 'checked';
							}
						}
					$html .=' id="checkbox'.$item->taxonomy_id.'"/>';
					$html .='<label for="checkbox'.$item->taxonomy_id.'">'.$item->taxonomy_name.'</label>';
					$html .= $this->category_relationships($data, $relationship, $item->taxonomy_id, $level);
					$html .='</li>';
				}
			}
			return $html;
		}

		function user_select($selected = '') {
			$this->load->model('user_model');
			$input['order'] = array('u_id', 'ASC');
			$data = $this->user_model->get_list($input);
			$html = '';
			foreach ($data as $key => $item) {
				if($selected == $item->u_id){
					$html .= '<option selected value="'.$item->u_id.'">'.$item->u_name.' ('.per_show($item->u_per).')</option>';
				}else{
					$html .= '<option value="'.$item->u_id.'">'.$item->u_name.' ('.per_show($item->u_per).')</option>';
				}
			}		
			
			return $html;
		}

		//Permission
		function Per_check(){
			if(ADMIN != ''){
				$admin = $this->uri->segment(1);
				$controller = $this->uri->segment(2);
				$user_info = $this->session->userdata('login');
				if($user_info && $admin == str_replace('/', '', ADMIN)){
					if(!Per_checkController($controller, $user_info->u_point)){
						redirect(base_url().ADMIN.'fail/permission');
					}
				}
			}else{
				$controller = $this->uri->segment(1);
				$user_info = $this->session->userdata('login');
				if($user_info){
					if(!Per_checkController($controller, $user_info->u_point)){
						redirect(base_url().ADMIN.'fail/permission');
					}
				}
			}
		}

		function roleCheck($id, $per, $user, $post){
			if($id == $user || $per == '1' || in_array($id, $this->config->item('super_admin'))){
				return true;
			}elseif($per == '2' || $per == '3'){
				$this->load->model('category_model');
				$ca_user = $this->category_model->category_id_by_user($id);
				$ca_posts = $this->category_model->category_id_by_posts($post);
				foreach ($ca_user as $key => $value) {
					if(in_array($value, $ca_posts)){
						return true;
					}
				}
				return false;
			}else{
				return false;
			}

		}

	}
?>