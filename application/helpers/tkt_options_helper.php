<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	if ( ! function_exists('per_radio')){
		function per_radio($sel = ''){
			$html = '';
			$html .= '<div class="myRadio">';

			$CI =& get_instance();
			$per_type  = $CI->config->item('per_type');
			$html = '';
			$html .= '<div class="myRadio">';
				foreach ($per_type as $value) {
					$html .= '<div class="radio radio-sm radio-'.$value['color'].' form-check-inline">';
						if(($value === end($per_type) && $sel == '') || $sel == $value['value'])
						$html .= '<input type="radio" checked name="data[per]" value="'.$value['value'].'" id="per_radio'.$value['value'].'" />';
						else $html .= '<input type="radio" name="data[per]" value="'.$value['value'].'" id="per_radio'.$value['value'].'" />';
						$html .= '<label for="per_radio'.$value['value'].'">'.$value['name'].'</label>';
					$html .= '</div>';
				}
			$html .= '</div>';
			return $html;
		}
	}

	if ( ! function_exists('per_show')){
		function per_show($sel = ''){
			$CI =& get_instance();
			$per_type  = $CI->config->item('per_type');
			foreach ($per_type as $value) {
				if($value['value'] == $sel){
					return '<span class="text-'.$value['color'].'">'.$value['name'].'</span>';
					break;
				}
			}
			return '<span class="text-'.$value['color'].'">'.$value['name'].'</span>';
		}
	}

	if ( ! function_exists('hot_radio')){
		function hot_radio($sel = ''){
			$CI =& get_instance();
			$hot  = $CI->config->item('hot');
			$html = '';
			$html .= '<div class="myRadio mgt10">';
				foreach ($hot as $key => $value) {
					$html .= '<div class="radio radio-sm radio-'.$value['color'].' form-check-inline">';
						if(($value === reset($hot) && $sel == '') || $sel == $value['value'])
						$html .= '<input type="radio" checked name="data[hot]" value="'.$value['value'].'" id="hot_radio'.$value['value'].'" />';
						else $html .= '<input type="radio" name="data[hot]" value="'.$value['value'].'" id="hot_radio'.$value['value'].'" />';
						$html .= '<label for="hot_radio'.$value['value'].'">'.$value['name'].'</label>';
					$html .= '</div>';
				}
			$html .= '</div>';
			return $html;

		}
	}

	if ( ! function_exists('hot_select')){
		function hot_select($sel = '', $all = true){
			$CI =& get_instance();
			$hot  = $CI->config->item('hot');
			$html = '';
			if($all){ $html .= '<option value="0">Trạng thái</option>';	}			
			foreach ($hot as $key => $value) {
				if($sel == $value['value'])
					$html .= '<option selected value="'.$value['value'].'">'.$value['name'].'</option>';
				else $html .= '<option value="'.$value['value'].'">'.$value['name'].'</option>';
			}
			$html .= '</div>';
			return $html;
		}
	}

	if ( ! function_exists('hot_show')){
		function hot_show($sel = ''){
			$CI =& get_instance();
			$hot  = $CI->config->item('hot');
			foreach ($hot as $value) {
				if($value['value'] == $sel){
					return $value['name'];
					break;
				}
			}
			return $hot[0]['name'];
		}
	}

	if ( ! function_exists('hot_icon')){
		function hot_icon($sel = '', $class = 'btn-warning btn-xs'){

			$CI =& get_instance();
			$hot  = $CI->config->item('hot');
			foreach ($hot as $value) {
				if($value['value'] == $sel && $value['icon'] != ''){
					return '<button type="button" data-toggle="tooltip" data-placement="top" title="'.$value['name'].'" class="btn '.$class.' "><i class="fa '.$value['icon'].'" aria-hidden="true"></i></button>';
					break;
				}
			}
			return '';
		}
	}

	if ( ! function_exists('status_radio')){
		function status_radio($sel = ''){

			$CI =& get_instance();
			$status  = $CI->config->item('status');
			$user_info = $CI->session->userdata('login');
			$html = '';	
			$html .= '<div class="myRadio mgt10">';
				foreach ($status as $key => $value){
					if($sel > 2 && $value['value'] <= 2){

					}else{
						if($value['value'] > 2 && $user_info->u_per == '4' ){

						}else{
							$html .= '<div class="radio radio-sm radio-'.$value['color'].' form-check-inline">';
								if(($value === reset($status) && $sel == '') || $sel == $value['value'])
								$html .= '<input type="radio" checked name="data[status]" value="'.$value['value'].'" id="status_radio'.$value['value'].'" />';
								else $html .= '<input type="radio" name="data[status]" value="'.$value['value'].'" id="status_radio'.$value['value'].'" />';
								$html .= '<label for="status_radio'.$value['value'].'">'.$value['name'].'</label>';
							$html .= '</div>';
						}
					}				
					
				}
			$html .= '</div>';
			return $html;

		}
	}

	if ( ! function_exists('status_show')){
		function status_show($sel = ''){
			$CI =& get_instance();
			$status  = $CI->config->item('status');
			foreach ($status as $value) {
				if($value['value'] == $sel){
					return $value['name'];
					break;
				}
			}
			return $status[0]['name'];
		}
	}

	if ( ! function_exists('status_select')){
		function status_select($sel = '', $all = true){
			$CI =& get_instance();
			$status  = $CI->config->item('status');
			$html = '';
			if($all){ $html .= '<option value="0">Tình trạng</option>';	}			
			foreach ($status as $key => $value) {
				if($sel == $value['value'])
					$html .= '<option selected value="'.$value['value'].'">'.$value['name'].'</option>';
				else $html .= '<option value="'.$value['value'].'">'.$value['name'].'</option>';
			}
			$html .= '</div>';
			return $html;
		}
	}

	if ( ! function_exists('type_radio')){
		function type_radio($sel = ''){
			$CI =& get_instance();
			$type  = $CI->config->item('type');
			$html = '';
			$html .= '<div class="myRadio mgt10">';
				foreach ($type as $key => $value) {
					$html .= '<div class="radio radio-sm radio-'.$value['color'].' form-check-inline">';
						if(($value === reset($type) && $sel == '') || $sel == $value['value'])
						$html .= '<input type="radio" checked name="data[type]" value="'.$value['value'].'" id="type_radio'.$value['value'].'" />';
						else $html .= '<input type="radio" name="data[type]" value="'.$value['value'].'" id="type_radio'.$value['value'].'" />';
						$html .= '<label for="type_radio'.$value['value'].'">'.$value['name'].'</label>';
					$html .= '</div>';
				}
			$html .= '</div>';
			return $html;

		}
	}

	if ( ! function_exists('type_icon')){
		function type_icon($sel = '', $class = 'btn-danger btn-xs'){

			$CI =& get_instance();
			$type  = $CI->config->item('type');
			foreach ($type as $value) {
				if($value['value'] == $sel && $value['icon'] != ''){
					return '<button type="button" data-toggle="tooltip" data-placement="top" title="'.$value['name'].'" class="btn '.$class.' "><i class="fa '.$value['icon'].'" aria-hidden="true"></i></button>';
					break;
				}
			}
			return '';
		}
	}

	if ( ! function_exists('type_select')){
		function type_select($sel = '', $all = true){
			$CI =& get_instance();
			$type  = $CI->config->item('type');
			$html = '';
			if($all){ $html .= '<option value="0">Loại bài viết</option>';	}			
			foreach ($type as $key => $value) {
				if($sel == $value['value'])
					$html .= '<option selected value="'.$value['value'].'">'.$value['name'].'</option>';
				else $html .= '<option value="'.$value['value'].'">'.$value['name'].'</option>';
			}
			$html .= '</div>';
			return $html;
		}
	}

	

	


	