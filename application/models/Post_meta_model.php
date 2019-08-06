<?php
	/**
	* Post Meta Model
	*/
	class Post_meta_model extends MY_Model{		
		function __construct(){
			parent::__construct();
			$this->table = 'post_meta';
			$this->key = 'pm_id';
			$this->order = array('pm_time', 'DESC');
		}

	}