<?php
	/**
	* Relationships Model
	*/
	class Relationships_model extends MY_Model{		
		function __construct(){
			parent::__construct();
			$this->table = 'relationships';
			$this->key = 'object_id';
			$this->order = array('object_id', 'DESC');
		}
	}
	
