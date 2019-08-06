<?php
	/**
	* Network Model
	*/
	class Network_model extends MY_Model{
		
		function __construct(){
			parent::__construct();
			$this->table = 'network';
			$this->key = 'n_id';
			$this->order = array('n_id', 'DESC');
        }
        
   	}