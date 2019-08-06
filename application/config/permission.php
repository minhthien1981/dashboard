<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
|  Permission Configuration
| -------------------------------------------------------------------
*/
// Max 60
//System Config 
$config['super_admin'] = array(1);
$config['per_max_point'] = '134217727';

// Config menu
$config['per']['default']       = array('point' => Per_getValue(0), 'name' => 'Mặt định', 'controller' => array('home', 'login', 'form', 'ajax', 'ckfinder', 'fail'));
$config['per']['user']          = array('point' => Per_getValue(1), 'name' => 'Quản lý thành viên');
$config['per']['setting']       = array('point' => Per_getValue(2), 'name' => 'Cài đặt chung');
$config['per']['posts']         = array('point' => Per_getValue(3), 'name' => 'Bài viết');
$config['per']['network']       = array('point' => Per_getValue(4), 'name' => 'Network');

// Config action
$config['per']['user']['view']     = array('point' => Per_getValue(7), 'name' => 'Views');
$config['per']['user']['add']       = array('point' => Per_getValue(8), 'name' => 'Add');
$config['per']['user']['edit']      = array('point' => Per_getValue(9), 'name' => 'Edit');
$config['per']['user']['delete']    = array('point' => Per_getValue(10), 'name' => 'Delete');

$config['per']['posts']['view']      = array('point' => Per_getValue(11), 'name' => 'Views');
$config['per']['posts']['add']       = array('point' => Per_getValue(12), 'name' => 'Add');
$config['per']['posts']['edit']      = array('point' => Per_getValue(13), 'name' => 'Edit');
$config['per']['posts']['delete']    = array('point' => Per_getValue(14), 'name' => 'Delete');