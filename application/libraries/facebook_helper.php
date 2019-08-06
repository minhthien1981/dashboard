<?php
function curl($url)
{	
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
function ConversationPost($access_token_page,$message_id,$message)
{
	$fql = 'https://graph.facebook.com/'.$message_id.'/messages?access_token='.$access_token_page.'&message='.$message.'&method=post';
	echo $fql;exit();
	try {
		$params = array(
			"message" => $message,
			"access_token_page" => $access_token_page,
		);
		return FbOAuth()->api('v2.9/'.$message_id.'/messages', 'POST', $params);
	}
	catch(Exception $e){
		print_r($e);exit();
		return false;
	}
	exit();
}
function Conversation($access_token_page,$message_id)
{
	$fql = 'https://graph.facebook.com/'.$message_id.'/messages?access_token='.$access_token_page;
	$data = json_decode(file_get_contents($fql),true);
	return $data['data'];
}
function Conversations($page_id,$access_token_page)
{
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
				'message'=>$value['snippet']
			);
		}
	}	
	return $conversations;
}
function ConversationOfAccount($access_token)
{
	$conversations = array();
	if($pages = FbOAuth_Pages($access_token))
	{
		foreach($pages['data'] as $key=>$value)
		{
			$conversations[$value['id']] = array(
				'id'=>$value['id'],
				'name'=>$value['name'],
				'message'=>Conversations($value['id'],$value['access_token']),
				'access_token'=>$value['access_token']
			);
		}	
	}
	return $conversations;
}
function recurse_it($url) 
{
    global $total;
    $feeds         = file_get_contents($url);
    $feed_data_obj = json_decode($feeds, true);
    if (!empty($feed_data_obj['data'])) 
	{
        $next_url      = $feed_data_obj['paging']['next'];
        $total += sizeof($feed_data_obj['data']);
        recurse_it($next_url);
    }
    return $total;
}
//tim kiếm group 
function FbOAuth_FindGroup($access_token,$keyword,$show_member=false)
{
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
			$data['data'][$key]['total_mem'] = System::display_number(recurse_it("https://graph.facebook.com/".$value['id']."/members?access_token=".$access_token.'&limit=10000'));
		}
	}
	return $data;
}
function get_image_attachment($post_id,$access_token,$object_id)
{
	$param = array(
		'access_token'  => $access_token,
		'method'        => 'GET',
		'q'             => 'SELECT attachment FROM stream WHERE source_id = me() AND post_id="'.$post_id.'"'
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
// lấy toàn bô post của 1 người dùng
function FbOAuth_PostOfUser($access_token,$uid='me',$return = false)
{
	$fql = 'https://graph.facebook.com/'.$uid.'/feed?access_token='.$access_token.($return?'&limit=20':'');
	$result = json_decode(file_get_contents($fql),true);
	if($return)
	{
		foreach($result['data'] as $key=>$value)
		{
			if($value['type']=='photo')
			{
				$result['data'][$key]['picture'] = get_image_attachment($value['id'],$access_token,$value['object_id']);
			}
		}
		 return $result;
	}
	$data = array();
	if(isset($result['data']) and $result['data'])
	{
		foreach($result['data'] as $key=>$value)
		{
			$data['data'][] = $value['from'];
		}
	}
	return ($data);
}
// lấy người theo dõi của 1 tài khoản
if (!function_exists("FbOAuth_FollowerOfUser")) {
	function FbOAuth_FollowerOfUser($access_token,$uid='me')
	{
		$fql = 'https://graph.facebook.com/'.$uid.'/subscribers?access_token='.$access_token;
		return json_decode(file_get_contents($fql),true);	
	}
}
// lấy group admin của 1 tài khoản
function FbOAuth_GroupAdmin($access_token)
{
	$fql = 'https://graph.facebook.com/me/groups?access_token='.$access_token;
	$data = json_decode(file_get_contents($fql),true);		
	if(isset($data['error']))
	{
		return $data;
	}
	$groups = array();
	foreach($data['data'] as $key=>$value)
	{
		if($value['administrator'])
		{
			$groups[] = $value;
		}
	}
	return $groups;
}
// lấy group của 1 tài khoản
if (!function_exists("FbOAuth_Groups")) {
	function FbOAuth_Groups($access_token,$uid='me')
	{
		try {
			$params = array(
				"fields=id,name,privacy",
				"limit" => 10000,
				"access_token" => $access_token
			);
			return FbOAuth()->api('/'.$uid.'/groups', 'GET', $params);
		}
		catch(Exception $e){
			return false;
		}
	}
}
// lấy tất cả thành viên của Group
if (!function_exists("FbOAuth_MemberGroups")) {
	function FbOAuth_MemberGroups($access_token,$uid='me')
	{
		try {
			$params = array(
				"fields=id,name,privacy",
				"limit" => 10000,
				"access_token" => $access_token
			);
			return FbOAuth()->api('/'.$uid.'/members', 'GET', $params);
		}
		catch(Exception $e){
			return false;
		}
	}
}
// lấy tất cả người dùng đã gửi yêu cầu kết bạn
if (!function_exists("FbOAuth_FriendPendding")) {
	function FbOAuth_FriendPendding($access_token)
	{
		$param = array(
			'access_token'  => $access_token,
			'method'        => 'GET',
			'q'             => 'SELECT name,mutual_friend_count,uid,sex FROM user WHERE uid in (SELECT uid_from FROM friend_request WHERE uid_to = me())'
		);
		$fql = 'https://graph.facebook.com/fql?' . http_build_query($param);
		return json_decode(file_get_contents($fql),true);
	}
}
// lấy tất cả post của Fanpage
if (!function_exists("FbOAuth_GetAllPostPage")) {
	function FbOAuth_GetAllPostPage($access_token,$page_id)
	{
		$param = array(
			'access_token'  => $access_token,
			'method'        => 'GET',
			'q'             => 'SELECT message,description FROM stream WHERE source_id='.$page_id
		);
		$fql = 'https://graph.facebook.com/fql?' . http_build_query($param);
		return json_decode(file_get_contents($fql),true);
	}
}
// mời bạn bè vào nhóm
if (!function_exists("FbOAuth_AddMemGroup")) {
	function FbOAuth_AddMemGroup($access_token,$uid,$groupid)
	{
		$fql = 'https://graph.facebook.com/'.$groupid.'/members/'.$uid.'?access_token='.$access_token.'&method=post';
		return json_decode(file_get_contents($fql),true);	
	}
}

// gửi yêu cầu kết bạn tới 1 tài khoản FB
if (!function_exists("FbOAuth_SendFriendRequest")) {
	function FbOAuth_SendFriendRequest($access_token,$friend_id)
	{
		$param = array(
			'uid'             => $friend_id,
			'method'        => 'post',
			'access_token'  => $access_token
		);
		$fql = 'https://graph.facebook.com/me/friends?'.http_build_query($param);
		return json_decode(curl($fql),true);	
	}
}
// get friends
if (!function_exists("FbOAuth_Friends")) {
	function FbOAuth_Friends($access_token,$uid = 'me')
	{
		try {
			$params = array(
				"fields=id,name",
				"access_token" => $access_token
			);
			return FbOAuth()->api('/'.$uid.'/friends', 'GET', $params);
		}
		catch(Exception $e) {
			return false;
		}
	}
}
if (!function_exists("FbOAuth_FriendOfUser")) 
{
	function FbOAuth_FriendOfUser($access_token,$uid='me')
	{
		$fql = 'https://graph.facebook.com/'.$uid.'/friends?access_token='.$access_token;
		return json_decode(file_get_contents($fql),true);
	}
}
if (!function_exists("get_total_like")) 
{
	function get_total_like($access_token,$page_id)
	{
		$fql = 'https://graph.facebook.com/v2.4/'.$page_id.'?fields=likes&access_token='.$access_token;
		return json_decode(file_get_contents($fql),true);
	}
}
function BuffLike($like_id,$access_token)
{
	try {
		$params = array(
			"access_token" => $access_token
		);
		return FbOAuth()->api('/'.$like_id.'/likes', 'POST', $params);
	}

	catch(Exception $e) {
		return false;
	}
}
function BuffComment($like_id,$message,$access_token)
{
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
if (!function_exists("FbOAuth_Pages")) {
	function FbOAuth_Pages($access_token)
	{
		try {
			$params = array(
				"fields=id,name,category",
				"limit" => 10000,
				"access_token" => $access_token
			);
			return FbOAuth()->api('/v2.3/me/accounts', 'GET', $params);
		}

		catch(Exception $e) {
			return false;
		}
	}
}
if (!function_exists("FbOAuth_User")) {
	function FbOAuth_User($access_token)
	{
		try {
			$params = array(
				"fields=id,name",
				"access_token" => $access_token
			);
			return FbOAuth()->api('/v2.3/me', 'GET', $params);
		}

		catch(Exception $e) {
			return false;
		}
	}
}

if (!function_exists("FbOAuth_Access_Token_Page")) {
	function FbOAuth_Access_Token_Page($pageid, $access_token)
	{
		try {
			$params = array(
				"access_token" => $access_token
			);
			$result = FbOAuth()->api('/v2.3/' . $pageid . '?fields=access_token', 'GET', $params);
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
}

if (!function_exists("FbOAuth_Info_App")) {
	function FbOAuth_Info_App($access_token)
	{
		$params = array(
			"access_token" => $access_token
		);
		return FbOAuth()->api('/v2.3/app', 'GET', $params);
	}
}

if (!function_exists("Fb_Post")) {
	function Fb_Post($data)
	{
		$response = array();
		if ($data->group_type == "page") {
			$data->access_token = FbOAuth_Access_Token_Page($data->group_id, $data->access_token);
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
				$response = FbOAuth()->api('/v2.3/' . $group . '/feed', "POST", $params);
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
				if (checkRemoteFile($image)) {
					$params["picture"] = $data->image;
				}
				$group = $data->group_type == "profile" ? "me" : $data->group_id;
				$response = FbOAuth()->api('/v2.3/' . $group . '/feed', "POST", $params);
				break;

			case 'image':
				$image = $data->image;
				if (checkRemoteFile($image)) {
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
				$id = getIdYoutube($url);
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
								$response = FbOAuth()->api('/v2.3/' . $data->group_id . '/videos', "POST", $params);
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
						$url = FB_DownloadVideo($url);
					}

					$params = array(
						"description" => $data->message,
						"file_url" => $url,
						"access_token" => $data->access_token
					);
					$response = FbOAuth()->api('/v2.3/' . $data->group_id . '/videos', "POST", $params);
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
					$post = FbOAuth()->api('/v2.3/' . $group_id . '/photos', "POST", $params);
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
					$response = FbOAuth()->api('/v2.3/' . $group_id . '/feed', "POST", $params);
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
}

if (!function_exists("FACEBOOK_GET_USER")) {
	function FACEBOOK_GET_USER()
	{
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
}

if (!function_exists("FACEBOOK_GET_LOGIN_URL")) {
	function FACEBOOK_GET_LOGIN_URL()
	{
		$FB = FbOAuth();
		return $FB->getLoginUrl(array(
			'scope' => 'email',
			'redirect_uri' => PATH . "openid/facebook"
		));
	}
}

if (!function_exists("FbOAuth")) {
	function FbOAuth()
	{
		require_once (ROOT_PATH . "skins/news/libraries/FbOAuth/facebook.php");

		$fb = new FacebookCustom(array(
			"appId" => FACEBOOK_ID,
			"secret" => FACEBOOK_SECRET
		));
		return $fb;
	}
}

if (!function_exists('FB_DownloadVideo')) {
	function FB_DownloadVideo($url)
	{
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
}

if (!function_exists('getIdYoutube')) {
	function getIdYoutube($link)
	{
		preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $link, $id);
		if (!empty($id)) {
			return $id = $id[0];
		}

		return $link;
	}
}

if (!function_exists('checkRemoteFile')) {
	function checkRemoteFile($url)
	{
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