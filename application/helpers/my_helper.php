<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	if( ! function_exists( 'public_url' )){
		function public_url($string = ''){
			return base_url('public/'.$string);
		}
	}

	if( ! function_exists( 'current_full_url' )){
		function current_full_url(){
			$protocol = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
			$base_url = $protocol . "://" . $_SERVER['HTTP_HOST'];
			$complete_url =   $base_url . $_SERVER["REQUEST_URI"];
			
			return $complete_url;
		}
	}

	if( ! function_exists( 'get_option' )){
		function get_option($string=''){
			$ci=& get_instance();
			$ci->load->database();
			$ci->db->select('option_value');
			$ci->db->where('option_name', $string);
			$row = $ci->db->get('options')->row();
			return $row->option_value;
		}
	}
	// Tạo ra License
	if( ! function_exists( 'encrypt' )){
		function encrypt($sData, $secretKey){
			$sResult = '';
			for($i=0;$i<strlen($sData);$i++){
				$sChar	= substr($sData, $i, 1);
				$sKeyChar = substr($secretKey, ($i % strlen($secretKey)) - 1, 1);
				$sChar	= chr(ord($sChar) + ord($sKeyChar));
				$sResult .= $sChar;

			}
			return encode_base64($sResult); 
		}
	}
	// Mã hóa lại License
	if( ! function_exists( 'decrypt' )){
		function decrypt($sData, $secretKey){
			$sResult = '';
			$sData   = decode_base64($sData);
			for($i=0;$i<strlen($sData);$i++){
				$sChar	= substr($sData, $i, 1);
				$sKeyChar = substr($secretKey, ($i % strlen($secretKey)) - 1, 1);
				$sChar	= chr(ord($sChar) - ord($sKeyChar));
				$sResult .= $sChar;
			}
			return $sResult;
		}
	}

	if( ! function_exists( 'encode_base64' )){
		function encode_base64($sData){
			$sBase64 = base64_encode($sData);
			return str_replace('=', '', strtr($sBase64, '+/', '-_'));
		}
	}

	if( ! function_exists( 'decode_base64' )){
		function decode_base64($sData){
			$sBase64 = strtr($sData, '-_', '+/');
			return base64_decode($sBase64.'==');
		}
	}

	if( ! function_exists( 'CreateAccessToken' )){
		function CreateAccessToken(){
			$day = (int)date("w");
			return encrypt('TKTAccessToken', $day);
		}
	}

	if( ! function_exists( 'CheckAccessToken' )){
		function CheckAccessToken($string){
			$day = (int)date("w");
			$decode = decrypt($string, $day);
			if (strpos($decode, 'TKTAccessToken') !== false) {
				return true;
			}else{
	            $decode = decrypt($string, ($day - 1));
	            if (strpos($decode, 'TKTAccessToken') !== false) {
	                return true;
	            }else{
	                return false;
	            }
	        }
		}
	}

	//Đoạn rút gọn ký tự
	if( ! function_exists( 'excerpt_char' )){
		function excerpt_char($string, $limit) {
			$limit++;
			$html = '';
			if ( mb_strlen( $string ) > $limit ) {
				$subex = mb_substr( $string, 0, $limit);
				$exwords = explode( ' ', $subex );
				$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
				if ( $excut < 0 ) {
					$html .= mb_substr( $subex, 0, $excut );
				} else {
					$html .= $subex;
				}
				$html = trim($html, ' ');
				$html .= '...';
			} else {
				$html .= $string;
			}
			return $html;
		}
	}

	//Đoạn rút gọn từ
	if( ! function_exists( 'excerpt_words' )){
		function excerpt_words($string, $limit) {
			$string = explode(' ', $string, $limit);
			if (count($string)>=$limit) {
				array_pop($string);
				$string = implode(" ",$string).'...';
			} else {
				$string = implode(" ",$string);
			} 
			$string = preg_replace('`[[^]]*]`','',$string);
			return $string;
		}
	}

	if( ! function_exists( 'formatNumber' )){
		function formatNumber($number) {
			return number_format((float)$number, 0, '', ',');
		}
	}


	//Premission
	
	function Per_checkController($controller, $point){
		$CI =& get_instance();
        $CI->config->load('permission');
		$per_list = $CI->config->item('per');
		if($controller == '') $controller = 'home';
		if(array_key_exists($controller, $per_list )){
			if(Per_checkBitwise($per_list[$controller]['point'], $point)){
				return true;
			}else{
				return false;
			}
		}elseif(in_array($controller, $per_list['default']['controller'])){
			return true;
		}else{
			return false;
		}
	}

	function Per_checkAction($controller, $action, $point){
		$CI =& get_instance();
		$CI->config->load('permission');
		if($controller == '') $controller = 'home';
		$per_list = $CI->config->item($controller, 'per');		
		if($action == '') $action = 'view';
		if(array_key_exists($action, $per_list )){
			if(Per_checkBitwise($per_list[$action]['point'], $point)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	function Per_getValue($num){
		return pow(2, $num);
	}

	function Per_setValue($data = array()){
		$point = 1;
		foreach($data as $selected){
			$permenu += $selected;
		}
		return $point;
	}
	
	function Per_checkBitwise($str1, $str2){
		if($str1 & $str2) : return true;
		else : return false;
		endif;
	}

	function Per_checkBitwiseCheckbox($str1, $str2){
		if($str1 & $str2) : return "checked='checked'";
		else : return "";
		endif;
	}
	//EndPermission
?>
