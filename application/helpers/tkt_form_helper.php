<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	if ( ! function_exists('tkt_form_input')){
		function tkt_form_input($name, $value = '', $title = '', $note = '', $append = ''){
			$id = str_replace("][","_", $name);
			$html = '';
			$html .= '<div class="form-group">';
				if($title != '') $html .= form_label($title, $id, array('class' => 'title-field'));
				if(is_array($append)){
					$append['name'] = 'data['.$name.']';
					$append['value'] = $value;
					$append['id'] = $id;
					$html .= form_input($append);
				}else{
					$html .= form_input('data['.$name.']', $value, ' id="'.$id.'" class="form-control"  autocomplete="off" '.$append);
				}			
				if($note != '') $html .= '<i class="note-field">'.$note.'</i>';
				$html .= '<p id="'.$id.'_error" class="note-field col-red">'.form_error('data['.$name.']').'</p>';
			$html .= '</div>';
			return $html;
		}
	}

	if ( ! function_exists('tkt_form_input_row')){
		function tkt_form_input_row($name, $value = '', $title = '', $note = '', $append = ''){
			$id = str_replace("][","_", $name);		
			$html = '';
			$html .= '<div class="form-group row">';
				if($title != '') $html .= form_label($title, $id, array('class' => 'title-field col-3 col-form-label'));
				$html .= '<div class="col-9">';
				if(is_array($append)){
					$append['name'] = 'data['.$name.']';
					$append['value'] = $value;
					$append['id'] = $id;
					$html .= form_input($append);
				}else{
					$html .= form_input('data['.$name.']', $value, ' id="'.$id.'" class="form-control"  autocomplete="off" '.$append);
				}	
				if($note != '') $html .= '<i class="note-field">'.$note.'</i>';
				$html .= '<p id="'.$id.'_error" class="note-field col-red">'.form_error('data['.$name.']').'</p>';
				$html .= '</div>';
			$html .= '</div>';
			return $html;
		}
	}

	if ( ! function_exists('tkt_form_radio_row')){
		function tkt_form_radio_row($name, $value = array(), $check = 0, $title = '', $note = '', $append = ''){
			$id = str_replace("][","_", $name);			
			$html = '';
			$html .= '<div class="form-group row">';
				if($title != '') $html .= form_label($title, $id, array('class' => 'title-field col-3 col-form-label'));
				$html .= '<div class="col-9">';
				$html .= '<div class="myRadio">';
					if($value){
						foreach ($value as $key => $item) {
							$checked = '';
							if($item['value'] == $check){ $checked = 'checked';}
							$html .='<div class="radio radio-sm radio-'.$item['color'].' form-check-inline"><input type="radio" '.$checked.' name="data['.$name.']" value="'.$item['value'].'" id="'.$id.'_radio'.$key.'"><label for="'.$id.'_radio'.$key.'">'.$item['name'].'</label></div>';
						}
					}
				$html .= '</div>';
				if($note != '') $html .= '<i class="note-field">'.$note.'</i>';
				$html .= '<p id="'.$id.'_error" class="note-field col-red">'.form_error('data['.$name.']').'</p>';
				$html .= '</div>';
			$html .= '</div>';
			return $html;
		}
	}

	if ( ! function_exists('tkt_form_number')){
		function tkt_form_number($name, $value = '', $title = '', $note = '', $max = '', $min = ''){
			$id = str_replace("][","_", $name);	
			$data = array(
				'name' 			=> 'data['.$name.']',
				'value'			=> $value,
				'id'			=> $id,
				'class'			=> 'form-control',
				'type' 			=> 'number',
				'autocomplete' 	=> 'off'
			);
			if($max != '') $data['max'] = $max;
			if($min != '') $data['min'] = $min;
			$html = '';
			$html .= '<div class="form-group">';
				if($title != '') $html .= form_label($title, $id, array('class' => 'title-field'));
				$html .= form_input($data);
				if($note != '') $html .= '<i class="note-field">'.$note.'</i>';
				$html .= '<p id="'.$id.'_error" class="note-field col-red">'.form_error('data['.$name.']').'</p>';
			$html .= '</div>';
			return $html;
		}
	}

	if ( ! function_exists('tkt_form_password')){
		function tkt_form_password($name, $value = '', $title = '', $note = '', $append = ''){
			$id = str_replace("][","_", $name);	
			$html = '';
			$html .= '<div class="form-group">';
				if($title != '') $html .= form_label($title, $id, array('class' => 'title-field'));
				$html .= form_password('data['.$name.']', $value, ' class="form-control" autocomplete="off" '.$append);
				if($note != '') $html .= '<i class="note-field">'.$note.'</i>';
				$html .= '<p id="'.$id.'_error" class="note-field col-red">'.form_error('data['.$name.']').'</p>';
			$html .= '</div>';
			return $html;
		}
	}

	if ( ! function_exists('tkt_form_textarea')){
		function tkt_form_textarea($name, $value = '', $title = '', $note = '', $append = ''){
			$id = str_replace("][","_", $name);	
			$html = '';
			$html .= '<div class="form-group">';
				if($title != '') $html .= form_label($title, $id, array('class' => 'title-field w-100'));
				$html .= '<textarea class="form-control" name="data['.$name.']" rows="3" type="text" id="'.$id.'" autocomplete="off" '.$append.'>'.$value.'</textarea>';
				if($note != '') $html .= '<i class="note-field">'.$note.'</i>';
				$html .= '<p id="'.$id.'_error" class="note-field col-red">'.form_error('data['.$name.']').'</p>';
			$html .= '</div>';
			return $html;
		}
	}

	if ( ! function_exists('tkt_form_textarea_row')){
		function tkt_form_textarea_row($name, $value = '', $title = '', $note = '', $append = ''){
			$id = str_replace("][","_", $name);	
			$html = '';
			$html .= '<div class="form-group row">';
				if($title != '') $html .= form_label($title, $id, array('class' => 'title-field col-3 col-form-label'));
				$html .= '<div class="col-9">';
				$html .= '<textarea class="form-control" name="data['.$name.']" rows="3" type="text" id="'.$id.'" autocomplete="off" '.$append.'>'.$value.'</textarea>';
				if($note != '') $html .= '<i class="note-field">'.$note.'</i>';
				$html .= '<p id="'.$id.'_error" class="note-field col-red">'.form_error('data['.$name.']').'</p>';
			$html .= '</div>';
			$html .= '</div>';
			return $html;
		}
	}

	if ( ! function_exists('tkt_form_ckeditor')){
		function tkt_form_ckeditor($name, $value = '', $title = '', $note = '', $append = ''){
			$id = str_replace("][","_", $name);	
			$html = '';
			$html .= '<div class="form-group">';
				if($title != '') $html .= form_label($title, $id, array('class' => 'title-field'));
				$html .= '<textarea class="ckeditor" name="data['.$name.']" type="text" id="'.$id.'" autocomplete="off" '.$append.'>'.$value.'</textarea>';
				if($note != '') $html .= '<i class="note-field">'.$note.'</i>';
				$html .= '<p id="'.$id.'_error" class="note-field col-red">'.form_error('data['.$name.']').'</p>';
			$html .= '</div>';
			return $html;
		}
	}

	if ( ! function_exists('tkt_form_img')){
		function tkt_form_img($name, $value = '', $title = '', $note = '', $append = ''){
			$id = str_replace("][","_", $name);	
			$html = '';
			$html .= '<div class="form-group">';
					if($title != '') $html .= form_label($title, $id, array('class' => 'title-field'));
					$html .= '<div class="featured-image-wp">';
						$html .= '<div class="featured-image">';
							$html .= '<input class="imgInp" name="data['.$name.']" id="'.$id.'" type="file" />';
							$html .= '<img class="this_img" src="'.$value.'" alt="'.$title.'" '.$append.' />';
						$html .= '</div>';
						$html .= '<div class="image_icon">Click to upload !</div>';
						if($note != '') $html .= '<i class="note-field">'.$note.'</i>';
					$html .= '</div>';			
				$html .= '</div>';
			return $html;
		}
	}

	if ( ! function_exists('tkt_form_ckfinder')){
		function tkt_form_ckfinder($name, $value = '', $title = '', $note = '', $append = ''){
			$id = str_replace("][","_", $name);	
			$html = '';
			$html .= '<div class="form-group">';
				if($title != '') $html .= form_label($title, $id, array('class' => 'title-field'));
				$html .= '<div class="featured-image-wp">';
					$html .= '<div class="featured-image">';
						$html .= '<input name="data['.$name.']" id="'.$id.'" type="hidden" />';
						$html .= '<img class="this_img w-100" id="img'.$id.'" src="'.$value.'" alt="'.$title.'" />';
					$html .= '</div>';
					$html .= '<button data-id="'.$id.'"  data-focus="img'.$id.'" type="button" class="btn btn-primary btnCkfinder w-100">Select File</button>';
					if($note != '') $html .= '<i class="note-field">'.$note.'</i>';
				   $html .= '</div>';
			$html .= '</div>';
			return $html;
		}
	}

	if ( ! function_exists('tkt_form_ckfinder_url')){
		function tkt_form_ckfinder_url($name, $value = '', $title = '', $note = '', $append = ''){
			$id = str_replace("][","_", $name);	
			$html = '';
			$html .= '<div class="form-group">';
				if($title != '') $html .= form_label($title, $id, array('class' => 'title-field'));
				$html .= '<div class="input-group mb-3">';
					$html .= '<input type="text" name="data['.$name.']" id="'.$id.'" class="form-control" value="'.$value.'" placeholder="Select URL" '.$append.'>';
					$html .= '<div class="input-group-append">';
						$html .= '<button class="btn btn-outline-secondary btnCkfinder" data-id="'.$id.'"  type="button">Select File</button>';
					$html .= '</div>';
				$html .= '</div>';
				if($note != '') $html .= '<i class="note-field">'.$note.'</i>';
			$html .= '</div>';
			return $html;
		}
	}


	if ( ! function_exists('tkt_form_submit')){
		function tkt_form_submit($name, $title = '', $class = 'btn-primary pull-right', $append = ''){
			$id = str_replace("][","_", $name);	
			$html = '<button type="submit" name="'.$name.'" id="'.$id.'" class="btn '.$class.'" '.$append.'>'.$title.'</button>';
			return $html;
		}
	}

	if ( ! function_exists('tkt_form_datetime')){
		function tkt_form_datetime($name, $value, $title = '', $format = 'H:m:s DD-MM-YYYY', $note = '', $append = ''){
			$id = str_replace("][","_", $name);	
			$html = '';
			$html .= '<div class="form-group">';
				if($title != '') $html .= '<label class="title-field" for="dtp_'.$id.'">'.$title.'</label>';
				$html .= '<div class="input-group">';
					$html .= '<span class="input-group-prepend">';
						$html .= '<div class="input-group-text bg-transparent"><i class="fa fa-calendar"></i></div>';
					$html .= '</span>';
					$html .= '<input type="text" name="data['.$name.']" id="dtp_'.$id.'" autocomplete="off" value="'.$value.'" class="form-control" '.$append.'/>';
				$html .= '</div>';
				if($note != '') $html .= '<i class="note-field">'.$note.'</i>';
			$html .= '</div>';
			$html .= '<script> $(document).ready(function(){ $("#dtp_'.$id.'").datetimepicker({format: "'.$format.'"});});</script>';
			return $html;
		}
	}
