<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
|  Options Configuration
| -------------------------------------------------------------------
*/

// Config menu
$config['hot']		= array(
						array('value' => 1, 'name' => 'Normal', 'color' => 'success', 'icon' => ''),
						array('value' => 2, 'name' => 'Hot', 'color' => 'info', 'icon' => 'fa-star'),
						array('value' => 3, 'name' => 'Sticky', 'color' => 'primary', 'icon' => 'fa-paperclip')
					);

$config['per_type']	= array(
						array('value' => 1, 'name' => 'Super Admin', 'color' => 'success'),
						array('value' => 2, 'name' => 'Admin', 'color' => 'primary'),
						array('value' => 3, 'name' => 'Moderators', 'color' => 'info'),
						array('value' => 4, 'name' => 'User', 'color' => 'secondary'),
						array('value' => 5, 'name' => 'Guest', 'color' => 'dark')
					);

$config['status']	= array(
						array('value' => 1, 'name' => 'Bản nháp', 'color' => 'secondary'),
						array('value' => 2, 'name' => 'Chờ duyệt', 'color' => 'primary'),
						array('value' => 3, 'name' => 'Đã duyệt', 'color' => 'info'),
						array('value' => 4, 'name' => 'Đã đăng', 'color' => 'success')
					);

$config['type']	= array(
						array('value' => 1, 'name' => 'Tin tức', 'color' => 'primary', 'icon' => ''),
						array('value' => 2, 'name' => 'Video', 'color' => 'success', 'icon' => 'fa-play')
					);

