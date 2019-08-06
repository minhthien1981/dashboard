<?php
	/**
	* 
	*/
	class Network extends My_controller {
		
		function __construct(){
			parent::__construct();
			$this->load->model('posts_model');
			$this->load->model('post_meta_model');
			$user_info = $this->session->userdata('login');
			if(!Per_checkController('network', $user_info->u_point)){
				redirect(base_url().ADMIN.'fail/permission');
			}
		}

		function index(){
			redirect(base_url().ADMIN);
		}

		function network_all(){
			$user_info = $this->session->userdata('login');
			
			$data = array();
			$data['layout'] = true;
			$data['active'] = 'network';
			$data['subactive'] = 'network_all';
			$data['title'] = "Admin &raquo Tất cả tin tức";
			$input['order'] = array('p_id','ASC');
			if((int)$user_info->u_per > 1){
				$input['where'] = array('p_type' => 1);
			}

			if(isset($_GET['start'])){
				$data['start'] = $_GET['start'];
			}else{
				$data['start'] = date('m-Y', strtotime('-1 month'));
			}

			if(isset($_GET['end'])){
				$data['end'] = $_GET['end'];
			}else{
				$data['end'] = date('m-Y', strtotime('-1 month'));
			}

			$data['posts'] = $this->posts_model->get_list_relationships($input, $user_info->u_id, $user_info->u_per);

			$this->load->model('network_model');
			$input2['order'] = array('n_order','ASC');
			$data['network'] = $this->network_model->get_list($input2);

			$this->load->view('backend/index', $data);
		}

		function network_edit(){
			$user_info = $this->session->userdata('login');	
			$data = array();
			$data['layout'] = true;
			$data['active'] = 'network';
			$data['subactive'] = 'network_edit';
			$data['title'] = "Admin &raquo Chỉnh sửa bài viết";

			if(ADMIN != ''){ $id = $this->uri->segment(4);}else{$id = $this->uri->segment(3);}
			if(is_numeric($id) && $id != ''){
				$data['posts'] = $this->posts_model->get_info($id);

				$this->load->model('network_model');
				$input2['order'] = array('n_order','ASC');
				$data['network'] = $this->network_model->get_list($input2);

				if($data['posts']){
					$input['order'] = array('pm_time', 'DESC');
					$input['where'] = array('pm_p_id' => $id);
					$data['post_meta'] = $this->post_meta_model->get_list($input);
					$data['relationships'] = $this->posts_model->posts_get_relationships($id);
					$data['user_select'] = $this->user_select();
				}else{
					$data = array('layout' => true, 'active' => 'error', 'subactive' => '404');
				}
			}else{
				redirect(base_url().ADMIN.'network/network_all');
			}
			
			$this->load->view('backend/index', $data);			
		}

		function network_detail(){
			$user_info = $this->session->userdata('login');	
			
			$data = array();
			$data['layout'] = true;
			$data['active'] = 'network';
			$data['subactive'] = 'network_detail';
			$data['title'] = "Admin &raquo Chỉnh sửa bài viết";
			if(ADMIN != ''){ $id = $this->uri->segment(4);}else{$id = $this->uri->segment(3);}
			if(is_numeric($id) && $id != ''){
				$data['posts'] = $this->posts_model->get_info($id);
				
				if($data['posts']){
					$input['order'] = array('pm_time', 'DESC');
					$input['where'] = array('pm_p_id' => $id);
					$data['post_meta'] = $this->post_meta_model->get_list($input);
					
				}else{
					$data = array('layout' => true, 'active' => 'error', 'subactive' => '404');
				}
			}else{
				redirect(base_url().ADMIN.'network/network_all');
			}
			
			$this->load->view('backend/index', $data);			
		}

		function export_excel(){
			$user_info = $this->session->userdata('login');

			if(!Per_checkAction('posts', 'view', $user_info->u_point)){
				echo "Bạn không có quyền truy cập trang này !";
				exit();
			}

			if(ADMIN != ''){ $id = $this->uri->segment(4);}else{$id = $this->uri->segment(3);}
			if(is_numeric($id) && $id != ''){
				$this->load->library('excel');
				$data = $this->posts_model->get_info($id);
				if($data){

					if( $user_info->u_per > 2 && $data->p_type == '1'){
						if($user_info->u_per == 3 && $this->posts_model->posts_check_relationships($id, $user_info->u_id)){}else{
							echo "Permission error";
							exit();
						}
					}

					$input['order'] = array('pm_time', 'DESC');
					$input['where'] = array('pm_p_id' => $id);
					$post_meta = $this->post_meta_model->get_list($input);

					$excel = new PHPExcel();
					//Chọn trang cần ghi (là số từ 0->n)
					$excel->setActiveSheetIndex(0);
					//Tạo tiêu đề cho trang. (có thể không cần)
					$excel->getActiveSheet()->setTitle($data->p_title);

					//Xét chiều rộng cho từng, nếu muốn set height thì dùng setRowHeight()
					$excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
					$excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
					$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);

					//Xét in đậm cho khoảng cột
					$excel->getActiveSheet()->getStyle('A1:E6')->getFont()->setBold(true);

					$excel->getActiveSheet()->setCellValue('A1', 'Channel: '.$data->p_title );
					$excel->getActiveSheet()->setCellValue('A2', 'Channel ID: '.$data->p_channel );
					$excel->getActiveSheet()->setCellValue('A3', 'Rate Network: '.($data->p_rate - $data->p_network_rate).'%' );
					$excel->getActiveSheet()->setCellValue('A4', 'Rate Sub Network: '.$data->p_network_rate.'%' );
					$excel->setActiveSheetIndex(0)->mergeCells('A1:E1');
					$excel->setActiveSheetIndex(0)->mergeCells('A2:E2');
					$excel->setActiveSheetIndex(0)->mergeCells('A3:E3');
					$excel->setActiveSheetIndex(0)->mergeCells('A4:E4');
					$excel->getActiveSheet()->setCellValue('A6', 'Tháng');
					$excel->getActiveSheet()->setCellValue('B6', 'Doanh Thu');
					$excel->getActiveSheet()->setCellValue('C6', 'Doanh Thu Network');
					$excel->getActiveSheet()->setCellValue('D6', 'Doanh Thu Sub Network');
					$excel->getActiveSheet()->setCellValue('E6', 'Doanh Thu Channel');
					// thực hiện thêm dữ liệu vào từng ô bằng vòng lặp
					// dòng bắt đầu = 2
					$numRow = 7;
					$total = 0;
					foreach ($post_meta as $row) {
						$total += $row->pm_value;
						if($data->p_rate != 0 && $row->pm_value != 0){
							$dt_network = $row->pm_value * (int)$data->p_rate / 100;
							$dt_subnet = $row->pm_value * (int)$data->p_network_rate / 100;
							$dt_channel = $row->pm_value - $dt_network;
							$dt_network = $dt_network - $dt_subnet;
						}else{
							$dt_network = 0;
							$dt_channel = $row->pm_value;
							$dt_subnet = 0;
						}


					    $excel->getActiveSheet()->setCellValue('A' . $numRow, date('m-Y', strtotime($row->pm_time)));
					    $excel->getActiveSheet()->setCellValue('B' . $numRow, $row->pm_value);
					    $excel->getActiveSheet()->setCellValue('C' . $numRow, $dt_network);
					    $excel->getActiveSheet()->setCellValue('D' . $numRow, $dt_subnet);
					    $excel->getActiveSheet()->setCellValue('E' . $numRow, $dt_channel);
					    $numRow++;
					}

					@$total_network = $total * (int)$data->p_rate / 100;
					@$total_subnet = $total * (int)$data->p_network_rate / 100;
					@$total_channel = $total - $total_network;
					@$total_network = $total_network - $total_subnet;


					$excel->getActiveSheet()->getStyle('A'.$numRow.':D'.$numRow)->getFont()->setBold(true);
					$excel->getActiveSheet()->setCellValue('A' . $numRow, 'Tổng:');
					$excel->getActiveSheet()->setCellValue('B' . $numRow, $total);
					$excel->getActiveSheet()->setCellValue('C' . $numRow, $total_network);
					$excel->getActiveSheet()->setCellValue('D' . $numRow, $total_subnet);
					$excel->getActiveSheet()->setCellValue('E' . $numRow, $total_channel);

					// Khởi tạo đối tượng PHPExcel_IOFactory để thực hiện ghi file
					// ở đây mình lưu file dưới dạng excel2007
					header('Content-type: application/vnd.ms-excel');
					header('Content-Disposition: attachment; filename="'.$data->p_channel.'.xls"');
					PHPExcel_IOFactory::createWriter($excel, 'Excel2007')->save('php://output');	
				}
			}
		}

		function export_excel_all(){
			$user_info = $this->session->userdata('login');

			if(!Per_checkAction('posts', 'view', $user_info->u_point)){
				echo "Bạn không có quyền truy cập trang này !";
				exit();
			}

			$input['order'] = array('p_id','ASC');
			if($user_info->u_per > 1){
				$input['where'] = array('p_type' => 2);
			}

			if(isset($_GET['start'])){
				$start = $_GET['start'];
			}else{
				$start = '';
			}

			if(isset($_GET['end'])){
				$end = $_GET['end'];
			}else{
				$end = '';
			}

			$data = $this->posts_model->get_list_relationships($input, $user_info->u_id, $user_info->u_per);

			$this->load->library('excel');

			$excel = new PHPExcel();
			//Chọn trang cần ghi (là số từ 0->n)
			$excel->setActiveSheetIndex(0);
			//Tạo tiêu đề cho trang. (có thể không cần)
			$excel->getActiveSheet()->setTitle("Export From ".$start." To ".$end);

			//Xét chiều rộng cho từng, nếu muốn set height thì dùng setRowHeight()
			$excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
			$excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
			$excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			$excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
			$excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
			$excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);

			//Xét in đậm cho khoảng cột
			$excel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);

			$excel->getActiveSheet()->setCellValue('A1', 'Channel Name' );
			$excel->getActiveSheet()->setCellValue('B1', 'Channel ID ');
			$excel->getActiveSheet()->setCellValue('C1', 'Network');			
			$excel->getActiveSheet()->setCellValue('D1', 'Doanh Thu');
			$excel->getActiveSheet()->setCellValue('E1', 'Rate');
			$excel->getActiveSheet()->setCellValue('F1', 'Doanh Thu Network');
			$excel->getActiveSheet()->setCellValue('G1', 'Doanh Thu Sub Network');
			$excel->getActiveSheet()->setCellValue('H1', 'Doanh Channel');
			// thực hiện thêm dữ liệu vào từng ô bằng vòng lặp
			// dòng bắt đầu = 2
			$numRow = 2;

			$this->load->model('network_model');
			$input2['order'] = array('n_order','ASC');
			$network = $this->network_model->get_list($input2);

			$total_doanhthu = 0;
			$total_network = 0;
			$total_subnet = 0;
			$total_channel = 0;

			foreach ($data as $row) {
				$total = $this->posts_model->total_doanhthu($row->p_id, $start, $end);

				if($row->p_rate != 0 && $total != 0){
					$dt_network = $total * (int)$row->p_rate / 100;
					$dt_subnet = $total * (int)$row->p_network_rate / 100;
					$dt_channel = $total - $dt_network;
					$dt_network = $dt_network - $dt_subnet;
				}else{
					$dt_channel = $total;
					$dt_subnet = 0;
					$dt_network = 0;
				}
				$network_text = '';
				$network_rate = '';
				foreach ($network as $n => $nw) {
					if($nw->n_id == $row->p_network){
						$network_text = $nw->n_name;
						$network_rate = $row->p_rate.'%';
					}
				}

				if($row->p_type == '1'){
					if($total != 0){
						$total_doanhthu += $total;
					}
					if($dt_network != 0){
						$total_network += $dt_network;
					}
					if($dt_subnet != 0){
						$total_subnet += $dt_subnet;
					}
					if($dt_channel != 0){
						$total_channel += $dt_channel;
					}
				}

			    $excel->getActiveSheet()->setCellValue('A' . $numRow, $row->p_title);
			    $excel->getActiveSheet()->setCellValue('B' . $numRow, $row->p_channel);
			    $excel->getActiveSheet()->setCellValue('C' . $numRow, $network_text);			    
			    $excel->getActiveSheet()->setCellValue('D' . $numRow, $total);
			    $excel->getActiveSheet()->setCellValue('E' . $numRow, ($row->p_rate - $row->p_network_rate).'-'.($row->p_network_rate).'-'.(100 -$row->p_rate));
			    $excel->getActiveSheet()->setCellValue('F' . $numRow, $dt_network);
			    $excel->getActiveSheet()->setCellValue('G' . $numRow, $dt_subnet);
			    $excel->getActiveSheet()->setCellValue('H' . $numRow, $dt_channel);
			    $numRow++;
			}

			$numRow++;
			$excel->getActiveSheet()->getStyle('A'.$numRow.':G'.$numRow)->getFont()->setBold(true);
			$excel->getActiveSheet()->setCellValue('A' . $numRow, "Tổng");		    
		    $excel->getActiveSheet()->setCellValue('D' . $numRow, $total_doanhthu);
		    $excel->getActiveSheet()->setCellValue('F' . $numRow, $total_network);
		    $excel->getActiveSheet()->setCellValue('G' . $numRow, $total_subnet);
		    $excel->getActiveSheet()->setCellValue('H' . $numRow, $total_channel);
		
			// Khởi tạo đối tượng PHPExcel_IOFactory để thực hiện ghi file
			// ở đây mình lưu file dưới dạng excel2007
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="Export From '.$start.' To '.$end.'.xls"');
			PHPExcel_IOFactory::createWriter($excel, 'Excel2007')->save('php://output');
		}
	}
?>