<?php defined('BASEPATH') OR exit('No direct script access allowed');
	class Fbsys{
		public function __construct(){
			log_message('Debug', 'Fbsys class is loaded.');
		}

		// Curl URL
		function curl($url){   
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 40);

			$data = curl_exec($ch);
			curl_close($ch);

			return $data;
		}

		function curl2($url) {
	        $ch = @curl_init();
	        curl_setopt($ch, CURLOPT_URL, $url);
	        $head[] = "Connection: keep-alive";
	        $head[] = "Keep-Alive: 300";
	        $head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
	        $head[] = "Accept-Language: en-us,en;q=0.5";
	        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
	        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
	        $page = curl_exec($ch);
	        curl_close($ch);
	        return $page;
	    }

		// Load thư viện Facebook
		public function FbOAuth(){
			require_once(APPPATH."/third_party/FbOAuth/facebook.php");
			$fb = new FacebookCustom(array(
				"appId" => APP_ID,
				"secret" => APP_SECRET
			));
			return $fb;
		}

		// Lấy thông tin quan ID
		function FbOAuth_Info($access_token, $id){
			try {
				$params = array(
					"fields=id,name",
					"access_token" => $access_token
				);
				return $this->FbOAuth()->api('/v2.3/'.$id, 'GET', $params);
			}

			catch(Exception $e) {
				return false;
			}
		}

		// Lấy thông tin user
		function FbOAuth_User($access_token){
			try {
				$params = array(
					"fields=id,name",
					"access_token" => $access_token
				);
				return $this->FbOAuth()->api('/v2.3/me', 'GET', $params);
			}

			catch(Exception $e) {
				return false;
			}
		}

		// Lấy thông tin App
		function FbOAuth_Info_App($access_token){
			$params = array(
				"access_token" => $access_token
			);
			return $this->FbOAuth()->api('/v2.3/app', 'GET', $params);
		}

		//Lấy token Page
		function FbOAuth_Access_Token_Page($pageid, $access_token){
			try {
				$params = array(
					"access_token" => $access_token
				);
				$result = $this->FbOAuth()->api('/v2.3/' . $pageid . '?fields=access_token', 'GET', $params);
				if (isset($result['access_token'])) {
					return $result['access_token'];
				}
				else {
					return false;
				}
			}

			catch(Exception $e) {
				return false;
			}
		}   

		// Lấy danh sách Page Quản Lý
		function FbOAuth_Pages($access_token){
			try {
				$params = array(
					"fields=id,name,category",
					"limit" => 10000,
					"access_token" => $access_token
				);
				return $this->FbOAuth()->api('/v2.3/me/accounts', 'GET', $params);
			}

			catch(Exception $e) {
				return false;
			}
		}

		// Change App Token
		function GetTokenNewApp($new_app_id,$access_token){
			$response = json_decode(file_get_contents('https://api.facebook.com/method/auth.getSessionforApp?access_token='.$access_token.'&format=json&new_app_id='.$new_app_id.'&generate_session_cookies=1'),true);
			if($response['access_token']){
				return $response['access_token'];
			}else{
				return false;
			}
		}

		// Get Cookies
		function GetCookies($access_token, $type = ''){
			$app_id = $this->FbOAuth_Info_App($access_token);
			if(!isset($app_id['error'])){
				$app_id = $app_id['id'];
				$response = json_decode(file_get_contents('https://api.facebook.com/method/auth.getSessionforApp?access_token='.$access_token.'&format=json&new_app_id='.$app_id.'&generate_session_cookies=1'), true);
				if(isset($response['session_cookies'])){
					if($type == 'string'){
						$result = "";
						foreach ($response['session_cookies'] as $key => $value) {
							$result .= $value['name']."=".$value['value']."; ";
						}
						return $result;
					}
					return $response['session_cookies'];
				}else{
					return false;
				}
			}
			return false;	
		}

		// Comment bài viết
		function BuffComment($like_id,$message,$access_token){
			$message = urlencode($message);
			return json_decode(file_get_contents('https://graph.facebook.com/'.$like_id.'/comments/?access_token='.$access_token.'&message='.$message.'&method=post'),true);
			/*try {
				$params = array(
					"access_token" => $access_token,
					"message"=>$message
				);
				return FbOAuth()->api('/'.$like_id.'/comments', 'POST', $params);
			}

			catch(Exception $e) {
				return false;
			}*/
		}

		//Like bài viết
		function BuffLike($like_id,$access_token){
			try {
				$params = array(
					"access_token" => $access_token
				);
				return $this->FbOAuth()->api('/'.$like_id.'/likes', 'POST', $params);
			}

			catch(Exception $e) {
				return false;
			}
		}

		//Like bài viết
		function BuffReactions($like_id, $access_token, $type = 'LIKE'){
			try {
				$params = array(
					"access_token" => $access_token,
					"type"		 => $type,
				);
				return $this->FbOAuth()->api('/'.$like_id.'/reactions', 'POST', $params);
			}

			catch(Exception $e) {
				return false;
			}
		}

		//Lấy số lượng like Page
		function get_total_like($access_token,$page_id){
			$fql = 'https://graph.facebook.com/v2.4/'.$page_id.'?fields=likes&access_token='.$access_token;
			$data = json_decode(file_get_contents($fql),true);
			if(isset($data["likes"]) && !empty($data["likes"])){
				return $data["likes"];
			}else{
				return 0;
			}
		}

		// Lấy danh sách bạn
		function FbOAuth_FriendOfUser($access_token, $uid='me'){
			$fql = 'https://graph.facebook.com/'.$uid.'/friends?access_token='.$access_token;
			return json_decode(file_get_contents($fql),true);
		}

		//Lấy danh sách bạn 2
		function FbOAuth_Friends($access_token, $uid = 'me'){
			try {
				$params = array(
					"fields=id,name",
					"access_token" => $access_token
				);
				return $this->FbOAuth()->api('/'.$uid.'/friends', 'GET', $params);
			}
			catch(Exception $e) {
				return false;
			}
		}

		// Gửi yêu cầu kết bạn đến 1 tài khoản
		function FbOAuth_SendFriendRequest($access_token, $friend_id){
			$param = array(
				'uid'			 => $friend_id,
				'method'		=> 'post',
				'access_token'  => $access_token
			);
			$fql = 'https://graph.facebook.com/me/friends?'.http_build_query($param);
			return json_decode($this->curl($fql),true);	
		}

		//Mời bạn bè vào nhóm
		function FbOAuth_AddMemGroup($access_token, $uid, $groupid){
			$fql = 'https://graph.facebook.com/'.$groupid.'/members/'.$uid.'?access_token='.$access_token.'&method=post';
			return json_decode(file_get_contents($fql), true);   
		}

		//Mời bạn bè vào nhóm
		function Ajax_AddMemGroup($access_token, $uid, $groupid){
			try {
				$params = array(
					'__a' => "1", 
					'__user' =>  "100013282838011",
					'source' =>  "typeahead",
					'ref' =>  "",
					'message_id' =>  "",
					'phstamp' =>  "",
					'fb_dtsg' =>  "AQFzCHBhtbka:AQFh5MKrVLYV",
					'group_id' =>  "1979549355608743",
					'members' =>  "100014048646175",
				);
				return $this->FbOAuth()->api('/groups/members/', 'POST', $params);
			}
			catch(Exception $e){
				return false;
			}
			//https://www.facebook.com/ajax/groups/members/add_post.php
		}

		//Lấy tất cả bài post của Fanpage
		function FbOAuth_GetAllPostPage($access_token,$page_id, $limit='100'){
			$param = array(
				'access_token'  => $access_token,
				'method'		=> 'GET',
				'q'			 => 'SELECT message,description,attachment FROM stream WHERE source_id='.$page_id.' LIMIT '.$limit
			);
			$fql = 'https://graph.facebook.com/fql?' . http_build_query($param);
			return json_decode(file_get_contents($fql),true);
		}

		//lấy tất cả người dùng đã gửi yêu cầu kết bạn
		function FbOAuth_FriendPendding($access_token){
			$param = array(
				'access_token'  => $access_token,
				'method'		=> 'GET',
				'q'			 => 'SELECT name,mutual_friend_count,uid,sex FROM user WHERE uid in (SELECT uid_from FROM friend_request WHERE uid_to = me())'
			);
			$fql = 'https://graph.facebook.com/fql?' . http_build_query($param);
			return json_decode(file_get_contents($fql),true);
		}

		// lấy tất cả thành viên của Group $uid là ID group
		function FbOAuth_MemberGroups($access_token, $uid='me'){
			try {
				$params = array(
					"fields=id,name,privacy",
					"limit" => 10000,
					"access_token" => $access_token
				);
				return $this->FbOAuth()->api('/'.$uid.'/members', 'GET', $params);
			}
			catch(Exception $e){
				return false;
			}
		}

		// lấy danh sách group của 1 tài khoản
		function FbOAuth_Groups($access_token,$uid='me'){
			try {
				$params = array(
					"fields=id,name,privacy",
					"limit" => 10000,
					"access_token" => $access_token
				);
				return $this->FbOAuth()->api('/'.$uid.'/groups', 'GET', $params);
			}
			catch(Exception $e){
				return false;
			}
		}

		// lấy danh sách group của 1 tài khoản có số lượng member
		function FbOAuth_Groups_Member($access_token, $uid='me'){
			$fql = 'https://graph.facebook.com/'.$uid.'/groups/?fields=id,name,member_count,privacy,unread&limit=10000&access_token='.$access_token;
			return json_decode(file_get_contents($fql),true);
		}

		// lấy danh sách nhóm quản lý
		function FbOAuth_GroupAdmin($access_token){
			$fql = 'https://graph.facebook.com/me/groups?access_token='.$access_token;
			$data = json_decode(file_get_contents($fql),true);	  
			if(isset($data['error'])){
				return $data;
			}
			$groups = array();
			foreach($data['data'] as $key=>$value){
				if($value['administrator']){
					$groups[] = $value;
				}
			}
			return $groups;
		}

		// lấy người theo dõi của 1 tài khoản 
		function FbOAuth_FollowerOfUser($access_token,$uid='me'){
			$fql = 'https://graph.facebook.com/'.$uid.'/subscribers?access_token='.$access_token;
			return json_decode(file_get_contents($fql),true);   
		}

		// lấy toàn bô post của 1 người dùng
		function FbOAuth_PostOfUser($access_token, $uid='me', $limit = '20', $return = false){
			$fql = 'https://graph.facebook.com/'.$uid.'/feed?access_token='.$access_token.($return?'&limit='.$limit:'');
			@$result = file_get_contents($fql);
			if($result){
				$result = json_decode($result,true);
			}else{
				$result = false;
			}			
			if($return && $result){
				foreach($result['data'] as $key=>$value){
					if($value['type']=='photo'){
						$result['data'][$key]['picture'] = $this->get_image_attachment($value['id'],$access_token,$value['object_id']);
					}
					$result['data'][$key]['created_time'] =  strtotime($result['data'][$key]['created_time']);
				}
				 return $result;
			}
			$data = array();
			if(isset($result['data']) and $result['data']){
				foreach($result['data'] as $key=>$value){
					$data['data'][] = $value['from'];
				}
			}
			return ($data);
		}

		// Lấy thông tin ảnh 
		function get_image_attachment($post_id,$access_token,$object_id){
			$param = array(
				'access_token'  => $access_token,
				'method'		=> 'GET',
				'q'			 => 'SELECT attachment FROM stream WHERE source_id = me() AND post_id="'.$post_id.'"'
			);
			$fql = 'https://graph.facebook.com/fql?' . http_build_query($param);
			$data = json_decode(file_get_contents($fql),true);  
			$image = array();
			if(isset($data['data'][0]['attachment']['media']) and ($images = $data['data'][0]['attachment']['media']))
			{
				foreach($images as $key=>$value)
				{
					$image[] = $value['photo']['images'][1]['src'];
				}
			}
			else
			{
				$fql = 'https://graph.facebook.com/'.$object_id.'/?access_token='.$access_token.'&fields=images';
				$data = json_decode(file_get_contents($fql),true);  
				$pic = array_shift($data);
				$image[] = $pic[0]['source'];
			}
			return json_encode($image);
		}

		//tim kiếm group 
		function FbOAuth_FindGroup($access_token,$keyword,$show_member=false){
			$param = array(
				'q'=>$keyword,
				'access_token'=>$access_token,
				'type'=>'group'
			);
			if($show_member)
			{
				$param['limit'] = 15;
			}
			$fql = 'https://graph.facebook.com/search?'.http_build_query($param);
			$data = json_decode(file_get_contents($fql),true);
			if($show_member and !empty($data['data']))
			{
				foreach($data['data'] as $key=>$value)
				{
					$data['data'][$key]['total_mem'] = $this->recurse_it("https://graph.facebook.com/".$value['id']."/members?access_token=".$access_token.'&limit=10000');
				}
			}
			return $data;
		}


		//Lấy số lượng member group khi tìm kiếm đang lỗi
		//Error
		function recurse_it($url){
			global $total;
			$feeds		 = file_get_contents($url);
			$feed_data_obj = json_decode($feeds, true);
			if (!empty($feed_data_obj['data'])) 
			{
				$next_url = $feed_data_obj['paging']['next'];
				$total += sizeof($feed_data_obj['data']);
				$this->recurse_it($next_url);
			}
			return $total;
		}

		// Lấy danh sách tin nhắn của Fanpage
		function ConversationOfAccount($access_token){
			$conversations = array();
			if($pages = $this->FbOAuth_Pages($access_token)){
				foreach($pages['data'] as $key=>$value)
				{
					$conversations[$value['id']] = array(
						'id'=>$value['id'],
						'name'=>$value['name'],
						'message'=>$this->Conversations($value['id'],$value['access_token']),
						'access_token'=>$value['access_token']
					);
				}   
			}
			return $conversations;
		}

		// Trả lời tin nhắn Fanpage
		function ConversationPost($access_token_page, $message_id, $message){
			$fql = 'https://graph.facebook.com/'.$message_id.'/messages?access_token='.$access_token_page.'&message='.$message.'&method=post';
			echo $fql;exit();
			try {
				$params = array(
					"message" => $message,
					"access_token_page" => $access_token_page,
				);
				return $this->FbOAuth()->api('v2.9/'.$message_id.'/messages', 'POST', $params);
			}
			catch(Exception $e){
				print_r($e);exit();
				return false;
			}
			exit();
		}

		//Code lỗi
		function Conversation($access_token_page,$message_id){
			$fql = 'https://graph.facebook.com/'.$message_id.'/messages?access_token='.$access_token_page;
			$data = json_decode(file_get_contents($fql),true);
			return $data['data'];
		}

		// Lấy tin nhắn của từng Page
		function Conversations($page_id, $access_token_page){
			$fql = 'https://graph.facebook.com/'.$page_id.'/conversations?access_token='.$access_token_page;
			$data = json_decode(file_get_contents($fql),true);
			$conversations = array();
			if(isset($data['data']) and $data['data'])
			{
				$data = $data['data'];
				foreach($data as $key=>$value)
				{
					$conversations[$value['id']]  = array(
						'id'=>$value['id'],
						'created_time'=>strtotime($value['updated_time']),
						'unread_count'=>$value['unread_count'],			 
						'message_count'=>$value['message_count'],							   
						'from_user_name'=>$value['senders']['data'][0]['name'],
						'from_email'=>$value['senders']['data'][0]['email'],
						'from_user'=>$value['senders']['data'][0]['id'],
						//Đang lỗi lấy tin - chưa xử lý		  
						//'message'=>$value['snippet']
					);
				}
			}   
			return $conversations;
		}

		//Post bài viết
		// Biến data 
		/*
			$data = array(
				'group_type' => 'page, group, profile', // 
				'access_token' => '',
				'group_id'  => 'ID',
				'type' => 'text, link, image, video, images',
				'privacy' => 'true, false',
				'message' => '',
				'name'  => '',
				'description' => '',
				'link'  => '',
				'caption' => '',
				'image' =>  ''
			);

		 */
		function Fb_Post($data){
			$response = array();
			if ($data->group_type == "page") {
				$data->access_token = $this->FbOAuth_Access_Token_Page($data->group_id, $data->access_token);
			}
			try {
				switch ($data->type) {
					case 'text':
						$params = array(
							"message" => $data->message,
							"access_token" => $data->access_token
						);
						if(($data->group_type=='profile') and $data->privacy)
						{
							$params['privacy'] = '{"value": "EVERYONE","description": "Public","friends": "","allow": "","deny": ""}';
						}
						$group = $data->group_type == "profile" ? "me" : $data->group_id;
						$response = $this->FbOAuth()->api('/v2.3/' . $group . '/feed', "POST", $params);
						break;

					case 'link':
						$params = array(
							"message" => $data->message,
							"name" => $data->title,
							"description" => $data->description,
							"link" => $data->url,
							"access_token" => $data->access_token
						);
						if ($data->caption != "") {
							$params["caption"] = $data->caption;
						}
						if(($data->group_type=='profile') and $data->privacy)
						{
							$params['privacy'] = '{"value": "EVERYONE","description": "Public","friends": "","allow": "","deny": ""}';
						}
						$image = $data->image;
						if ($this->checkRemoteFile($image)) {
							$params["picture"] = $data->image;
						}
						$group = $data->group_type == "profile" ? "me" : $data->group_id;
						$response = $this->FbOAuth()->api('/v2.3/' . $group . '/feed', "POST", $params);
						break;

					case 'image':
						$image = $data->image;
						if ($this->checkRemoteFile($image)) {
							$params = array(
								"message" => $data->message,
								"access_token" => $data->access_token
							);
							$params["url"] = $image;
							$group_id = ($data->group_type == "profile") ? "me" : $data->group_id;
							$response = FbOAuth()->api('/v2.3/' . $group_id . '/photos', "POST", $params);
						}

						break;

					case 'video':
						$url = $data->image;
						$id = $this->getIdYoutube($url);
						if (strlen($id) == 11) {
							parse_str(file_get_contents('http://www.youtube.com/get_video_info?video_id=' . $id) , $info);
							if ($info['status'] == "ok") {
								$streams = explode(',', $info['url_encoded_fmt_stream_map']);
								$type = "video/mp4";
								foreach($streams as $stream) {
									parse_str($stream, $real_stream);
									$stype = $real_stream['type'];
									if (strpos($real_stream['type'], ';') !== false) {
										$tmp = explode(';', $real_stream['type']);
										$stype = $tmp[0];
										unset($tmp);
									}

									if ($stype == $type && ($real_stream['quality'] == 'large' || $real_stream['quality'] == 'medium' || $real_stream['quality'] == 'small')) {
										$params = array(
											"description" => $data->message,
											"file_url" => $real_stream['url'] . '&signature=' . @$real_stream['sig'],
											"access_token" => $data->access_token
										);
										$response = $this>FbOAuth()->api('/v2.3/' . $data->group_id . '/videos', "POST", $params);
									}
								}
							}
							else {
								$response = array(
									"st" => "error",
									"txt" => strip_tags($info['reason'])
								);
							}
						}
						else {
							if (strpos($url, 'facebook.com') != false) {
								$url = $this->FB_DownloadVideo($url);
							}

							$params = array(
								"description" => $data->message,
								"file_url" => $url,
								"access_token" => $data->access_token
							);
							$response = $this->FbOAuth()->api('/v2.3/' . $data->group_id . '/videos', "POST", $params);
						}

						break;

					case 'images':
						$images = json_decode($data->image);
						$medias = array();
						foreach($images as $image) {
							$params = array(
								"message" => $data->message,
								"access_token" => $data->access_token,
								"published" => false
							);
							$params["url"] = $image;
							$group_id = ($data->group_type == "profile") ? "me" : $data->group_id;
							$post = $this->FbOAuth()->api('/v2.3/' . $group_id . '/photos', "POST", $params);
							if (isset($post['id'])) {
								$medias[] = $post['id'];
							}
						}

						if (!empty($medias)) {
							$params = array(
								"message" => $data->message,
								"access_token" => $data->access_token
							);
							foreach($medias as $key => $media) {
								$params["attached_media[" . $key . "]"] = '{"media_fbid":"' . $media . '"}';
							}

							$group_id = ($data->group_type == "profile") ? "me" : $data->group_id;
							$response = $this->FbOAuth()->api('/v2.3/' . $group_id . '/feed', "POST", $params);
							if (isset($response["id"])) {
								$find_id = explode("_", $response["id"]);
								$response = array(
									"id" => $find_id[1]
								);
							}
						}

						break;
				}

				if (isset($response["id"]) || (isset($response["st"]) && $response["st"] == "success")) {
					$response = array(
						"st" => "success",
						"txt" => isset($response["txt"]) ? $response["txt"] : "",
						"id" => isset($response["id"]) ? $response["id"] : ""
					);
				}
				else {
					if (isset($response["error"]) || isset($response["st"])) {
						$response = array(
							"st" => "error",
							"txt" => isset($response["txt"]) ? $response["txt"] : $response["error"]["message"]
						);
					}
					else {
						$response = array(
							"st" => "error",
							"txt" => "Unknow error"
						);
					}
				}
			}

			catch(FacebookExceptionsFacebookResponseException $e) {
				$response = array(
					"st" => "error",
					"txt" => $e->getMessage()
				);
			}

			catch(FacebookExceptionsFacebookSDKException $e) {
				$response = array(
					"st" => "error",
					"txt" => $e->getMessage()
				);
			}

			return $response;
		}

		function FACEBOOK_GET_USER(){
			$FB = FbOAuth();
			$access_token = $FB->getAccessToken();
			try {
				$params = array(
					"fields=id,name,email",
					"access_token" => $access_token
				);
				return $FB->api('/v2.7/me', 'GET', $params);
			}

			catch(Exception $e) {
				return false;
			}
		}

		function FACEBOOK_GET_LOGIN_URL(){
			$FB = FbOAuth();
			return $FB->getLoginUrl(array(
				'scope' => 'email',
				'redirect_uri' => PATH . "openid/facebook"
			));
		}

		function getFaceookVideoID($link){
			
	        if(substr($link, -1) != '/' && is_numeric(substr($link, -1))){
	            $link = $link.'/';
	        }
	        preg_match('/https:\/\/www.facebook.com\/(.*)\/videos\/(.*)\/(.*)\/(.*)/U', $link, $id); // link dạng https://www.facebook.com/userName/videos/vb.IDuser/IDvideo/?type=2&theater
	        if(isset($id[4])){
	            $idVideo = $id[3];
	        }else{
	            preg_match('/https:\/\/www.facebook.com\/(.*)\/videos\/(.*)\/(.*)/U', $link, $id); // link dạng https://www.facebook.com/userName/videos/IDvideo
	            if(isset($id[3])){
	                $idVideo = $id[2];
	            }else{
	                preg_match('/https:\/\/www.facebook.com\/video\.php\?v\=(.*)/', $link, $id); // link dạng https://www.facebook.com/video.php?v=IDvideo
	                $idVideo = $id[1];
	                $idVideo = substr($idVideo, 0, -1);
	            }
	        }
	        return $idVideo;
	    }

		function getFacebookLinkVideo($link){
		   	$idVideo = $this->getFaceookVideoID($link);

			$embed = 'https://www.facebook.com/video/embed?video_id='.$idVideo; // đưa link về dạng embed
			$get = $this->curl2($embed);

			//Code moi
			preg_match("'hd_src(.*?)hd_tag'si", $get, $data);
			if(isset($data[0])){
				$data = explode('"', $data[0]);
			}
			if(isset($data[2])){
				$return = $data[2];// link download HD
				$return = str_replace('\\', '', $return);
			}elseif(isset($data[6])){
				$return = $data[6];// link download SD
				$return = str_replace('\\', '', $return);
			}else{
				$return = false;
			}

			return $return;
		}


		function FB_DownloadVideo($url){
			$useragent = 'Mozilla/5.0 (Linux; U; Android 2.3.3; de-de; HTC Desire Build/GRI40) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			$source = curl_exec($ch);
			curl_close($ch);
			$download = explode('/video_redirect/?src=', $source);
			if (isset($download[1])) {
				$download = explode('&amp', $download[1]);
				$download = rawurldecode($download[0]);
				return $download;
			}

			return "error";
		}

		function getIdYoutube($link){
			preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $link, $id);
			if (!empty($id)) {
				return $id = $id[0];
			}

			return $link;
		}

		function checkRemoteFile($url){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_NOBODY, 1);
			curl_setopt($ch, CURLOPT_FAILONERROR, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			if (curl_exec($ch) !== FALSE) {
				return true;
			}
			else {
				return false;
			}
		}

	}