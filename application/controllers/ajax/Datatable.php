<?php
	/**
	* Data Table
	*/
	class Datatable extends MY_Controller {
		
		function __construct(){
			parent::__construct();
		}

        function index(){

        }

        function posts_all(){
			$this->load->model('posts_model');
			$this->load->helper('tkt_options_helper');
			$user_info = $this->session->userdata('login');	
	
			$params = $columns = $totalRecords = $data = array();
			 
			$params = $_REQUEST;
			 
			$columns = array(
				0 => 'p_id',
				1 => 'p_id',
				2 => 'p_title', 
				3 => 'p_channel',
				4 => '',
				5 => 'p_type',
				6 => ''
			);
			 
			$where_condition = $sqlTot = $sqlRec = "";

			$where_condition .=" WHERE 1=1 ";

			if($user_info->u_per > '4'){
				$where_condition .=" AND p_type = 2 ";
			}

			if($params['columns'][0]['search']['value'] != '' && $params['columns'][0]['search']['value'] != 0){
				$where_condition .= " AND p_type = ".$params['columns'][0]['search']['value']." ";
			}

	
			if( !empty($params['search']['value']) ) {
				$where_condition .=	" AND ";
				$where_condition .= " ( p_id LIKE '%".$params['search']['value']."%' ";    
				$where_condition .= " OR p_title LIKE '%".$params['search']['value']."%' ";
				$where_condition .= " OR p_channel LIKE '%".$params['search']['value']."%' )";
			}
			 
			$sql_query = " SELECT DISTINCT p_id, p_user_id, p_title, p_channel, p_type, p_date FROM posts ";
			
			$sqlTot .= $sql_query;
			$sqlRec .= $sql_query;
				
			if(isset($where_condition) && $where_condition != '') {        
				$sqlTot .= $where_condition;
				$sqlRec .= $where_condition;
			}
			
		   
	
			$sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";
			
			$totalRecords = $this->db->query($sqlTot)->num_rows();
	
			$queryRecords = $this->db->query($sqlRec)->result();
			
			foreach ($queryRecords as $key => $item) {

			
				
		
				$data[$key][0] = '<div align="center"><input name="ick[]" type="checkbox" class="css-checkbox check_items" id="md_checkbox_'.$key.'" value="'.$item->p_id.'"><label class="css-label lite-cyan-check2" for="md_checkbox_'.$key.'">&nbsp;</label></div>';
				$data[$key][1] = '<div align="center">'.$item->p_id.'</div>';
				
				$data[$key][2] = '<td><div align="left"><a href="'.base_url().ADMIN.'posts/posts_edit/'.$item->p_id.'">'.$item->p_title.'</a></div></td>';

				$data[$key][3] = '<td><div align="center">#</div></td>';
				$data[$key][4] = '<td><div align="center">'.$item->p_channel.'</div></td>';
				$xn = 5;
				if(Per_checkAction('posts', 'edit', $user_info->u_point)):
					$data[$key][$xn] = '<td><div align="center">'.$item->p_type.'</div></td>';
					$xn++;
				endif;
				$data[$key][$xn] =  '<td><div align="center">';
				$data[$key][$xn] .= '<a href="'.base_url().ADMIN.'posts/posts_detail/'.$item->p_id.'" target="_blank" ><button class="btn btn-info btn-sm" type="button"><i class="fa fa-eye" aria-hidden="true"></i></button></a> ';
				
				if(Per_checkAction('posts', 'edit',$user_info->u_point)){
					$data[$key][$xn] .= '<a href="'.base_url().ADMIN.'posts/posts_edit/'.$item->p_id.'" ><button	class="btn btn-primary btn-sm" type="button"><i class="fa fa-pencil" aria-hidden="true"></i></button></a> ';
				}
				if(Per_checkAction('posts', 'delete' ,$user_info->u_point)){
					$data[$key][$xn] .= '<button type="button" id="'.$item->p_id.'" class="delete_item_table btn btn-danger btn-sm" onclick="del('.$item->p_id.', \''.base_url().'form/posts/delete'.'\')"><i class="fa fa-trash" aria-hidden="true"></i></button>';
				}
								
				$data[$key][$xn] .= '</div></td>';
			}
			
			$json_data = array(
				"draw"            => intval( $params['draw'] ),   
				"recordsTotal"    => intval( $totalRecords ),  
				"recordsFiltered" => intval($totalRecords),
				"data"            => $data
			);
			
			echo json_encode($json_data);
		}
    }