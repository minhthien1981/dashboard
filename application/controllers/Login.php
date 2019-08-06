<?php
	/**
	* Login
	*/
	class Login extends My_controller{
		
		function __construct(){
			parent::__construct();
			$this->load->model('user_model');
		}

		function index(){
			//Nếu đã login redirect về trang admin
			if($this->_user_is_login()){
				redirect(base_url().ADMIN);
			}

			// Check nếu tồn tại cookie
			$this->load->helper('cookie');
			$userlogin = decrypt(get_cookie('userloginTKT'),'tkt');
			$passlogin = decrypt(get_cookie('passloginTKT'),'tkt');
			$gmailLogin = decrypt(get_cookie('userloginGmail'),'tkt');

			if(isset($userlogin) && isset($passlogin) && $userlogin !='' && $passlogin !=''){
				$where = array('u_user' => $userlogin, 'u_pass' => $passlogin);
				if($this->user_model->check_exists($where)){
					//Lấy thông tin thành viên
	            	$user = $this->user_model->get_info_rule($where);
	             
	            	//Lưu thông tin thành viên vào session
	           		$this->session->set_userdata('login', $user);
					redirect(base_url().ADMIN);
				}
			}

			if(isset($gmailLogin) && $gmailLogin !=''){
				$where = array('u_email' => $gmailLogin);
				if($this->user_model->check_exists($where)){
					//Lấy thông tin thành viên
	            	$user = $this->user_model->get_info_rule($where);
	             
	            	//Lưu thông tin thành viên vào session
	           		$this->session->set_userdata('login', $user);
					redirect(base_url());
				}
			}

			$this->form_validation->set_rules('username', 'username', 'required');
       		$this->form_validation->set_rules('password', 'password', 'required');
       		$this->form_validation->set_rules('login', 'Đăng nhập', 'callback_check_login');

       		if($this->form_validation->run()){
	            // Lấy dữ liệu từ form
	            $userlogin = addslashes($this->input->post('username'));
        		$passlogin = sha1(md5(sha1(addslashes($this->input->post('password')))));
        		$where = array('u_user' => $userlogin, 'u_pass' => $passlogin);

	            //Lấy thông tin thành viên
	            $user = $this->user_model->get_info_rule($where);

	            // Khởi tạo cookie
				$number_of_days = 30 ;
				$date_of_expiry = 60 * 60 * 24 * $number_of_days;
            	set_cookie(array('name' => 'userloginTKT', 'value'  => encrypt($user->u_user,'tkt'), 'expire' => $date_of_expiry ));
            	set_cookie(array('name' => 'passloginTKT', 'value'  => encrypt($user->u_pass,'tkt'), 'expire' => $date_of_expiry ));

	            //Lưu thông tin thành viên vào session
	            $this->session->set_userdata('login', $user);
	            //tạo thông báo
	            $this->session->set_flashdata('flash_message', 'Đăng nhập thành công !');
	            redirect(base_url().ADMIN);
			}
			$data['username'] = $this->input->post('username');
			$data['title'] = 'Login';
			$this->load->view('backend/login', $data);
		}

		private function _user_is_login(){
		    $user_data = $this->session->userdata('login');

		    if(!$user_data) {
		        return false;
		    }
		    return true;
		}

		/*
	    * Kiểm tra đăng nhập
	    */
	    public function check_login(){
	        //lay du lieu tu form
	        $userlogin = addslashes($this->input->post('username'));
        	$passlogin = sha1(md5(sha1(addslashes($this->input->post('password')))));
        	$where = array('u_user' => $userlogin, 'u_pass' => $passlogin);
	        if(!$this->user_model->check_exists($where)) {
	             //trả về thông báo lỗi nếu đã tồn tại email này
	            $this->form_validation->set_message(__FUNCTION__, 'Tài khoản hoặc mật khẩu ko đúng !');
	            return FALSE;
	        }
	        return true;
	    }
	    /**
	     * Đăng xuất
	     */
	    public function logout(){
			//if($this->_user_is_login()){
				$this->load->helper('cookie');
				//Nếu thành viên đăng nhập thì xóa session
				$this->session->unset_userdata('login');
				delete_cookie('userloginTKT');
				delete_cookie('passloginTKT');
				delete_cookie('userloginGmail');
			//}
			$this->session->set_flashdata('flash_message', 'Đăng xuất thành công !');
			redirect(base_url().ADMIN.'login');
		}

		function with_google(){
			$this->load->library('google');
			if(isset($_GET['code'])){
				$this->load->helper('cookie');
	            //authenticate user
	            $this->google->getAuthenticate();
	            
	            //get user info from google
	            $gpInfo = $this->google->getUserInfo();
	            
	            //preparing data for database insertion
	            // $userData['oauth_provider'] = 'google';
	            // $userData['oauth_uid']      = $gpInfo['id'];
	            // $userData['first_name']     = $gpInfo['given_name'];
	            // $userData['last_name']      = $gpInfo['family_name'];
	            // $userData['email']          = $gpInfo['email'];
	            // $userData['gender']         = !empty($gpInfo['gender'])?$gpInfo['gender']:'';
	            // $userData['locale']         = !empty($gpInfo['locale'])?$gpInfo['locale']:'';
	            // $userData['profile_url']    = !empty($gpInfo['link'])?$gpInfo['link']:'';
	            // $userData['picture_url']    = !empty($gpInfo['picture'])?$gpInfo['picture']:'';
				
				$userData['u_appID'] = $gpInfo['id'];
				$userData['u_name'] = $gpInfo['given_name'].' '.$gpInfo['family_name'];
				$userData['u_email'] = $gpInfo['email'];
				$userData['u_img'] = !empty($gpInfo['picture'])?$gpInfo['picture']:' ';
	            	           
				$userData = (object)$userData;
	            //insert or update user data to the database
	            $userInfo = $this->user_model->checkUser($userData);	            
				
	            $this->session->set_userdata('login', $userInfo);
	            // Khởi tạo cookie
				$number_of_days = 30 ;
				$date_of_expiry = 60 * 60 * 24 * $number_of_days;
            	set_cookie(array('name' => 'userloginGmail', 'value'  => encrypt($userInfo->u_email,'tkt'), 'expire' => $date_of_expiry ));
	            
	            //redirect to profile page
	            redirect(base_url());
	            
	        }
	        
	        //google login url
	        redirect( $this->google->loginURL() );	        
		}
	}
?>