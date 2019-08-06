<script type="text/javascript">
	$(document).ready(function(){		
		$('#myTable').dataTable({
			"aoColumnDefs": [{ 'bSortable': false, 'aTargets': [ 0, 5 ] }],
			"pageLength": 25,
			"order": []
		});	
	});
	
</script>
<?php $user_info = $this->session->userdata('login'); ?>
<!-- All user -->


<div class="col-md-12 col-xs-12 col-sm-12">
	<div class="option-heading">
		<div class="arrow-up">+</div>
		<div class="arrow-down">-</div>
		<span>Tất cả quản trị viên</span>
	</div>
	<div class="option-content">
		<div class="row">
			<div class="action-heading col-12">
				<div class="btn-group" role="group">
					<div class="btn-group" role="group">
						<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Action <i class="caret"></i>
						</button>
						<div class="dropdown-menu">
							<?php if(Per_checkAction('user', 'delete', $user_info->u_point)): ?>
								<a class="dropdown-item" data-action="delete" onclick="delAll('<?php echo base_url() ?>form/user/delete_multi')">Xóa các mục đã chọn</a>
							<?php endif;?>
						</div>
					</div>
					<?php if(Per_checkAction('user', 'add', $user_info->u_point)): ?>
					<a href="<?php echo  base_url().ADMIN?>user/user_add" class="btn btn-success btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Thêm mới</a>
					<?php endif;?>
				</div>
			</div>
			<div class="main-content col-12">
				<form name="myform" id="myform" action="" method="POST">
					<table id="myTable" class="display table table-striped table-bordered" width="100%">
						<thead>
						<tr>
							<th>
								<input id="checkall" type="checkbox" onclick="CheckAll('check_items', this)" name="check_all" class="css-checkbox">
								<label for="checkall" class="css-label lite-cyan-check2"></label>
							</th>
							<th>ID</th>
							<th>Tên</th>
							<th>Email</th>
							<th>Chức vụ</th>
							<?php							
								if(Per_checkAction('user', 'edit',$user_info->u_point) || Per_checkAction('user', 'delete' ,$user_info->u_point)):
									echo '<th>Hành động</th>';
								endif;
							?>
						</tr>
						</thead>
						<?php			
							foreach ($posts as $key => $item) {							
								echo '
								<tr id="'.$item->u_id.'">
									<td width="2%" align="center">
										<div align="center"><input name="ick[]" type="checkbox" class="css-checkbox check_items" id="md_checkbox_'.$key.'" value="'.$item->u_id.'"><label class="css-label lite-cyan-check2" for="md_checkbox_'.$key.'">&nbsp;</label></div>
									</td>
									<td><div align="center">'.$item->u_id.'</div></td>
									<td><div align="center">'.$item->u_name.'</div></td>
									<td><div align="center">'.$item->u_email.'</div></td>
									<td><div align="center">'.per_show($item->u_per).'</div></td>';
									if(Per_checkAction('user', 'edit',$user_info->u_point) || Per_checkAction('user', 'delete' ,$user_info->u_point)):
										echo '<td><div align="center">';
										if(Per_checkAction('user', 'edit',$user_info->u_point)){
											echo '<a href="'.base_url().ADMIN.$active.'/user_edit/'.$item->u_id.'" ><button	class="btn btn-sm btn-primary" type="button"><i class="fa fa-pencil" aria-hidden="true"></i></button></a> ';
										}
										if(Per_checkAction('user', 'delete' ,$user_info->u_point)){
											echo '<button type="button" id="'.$item->u_id.'" class="delete_item_table btn btn-sm btn-danger" onclick="del('.$item->u_id.', \''.base_url().'form/user/delete'.'\')"><i class="fa fa-trash" aria-hidden="true"></i></button>';
										}								
										echo '</div></td>';
									endif;
									
								echo '</tr>';
							}
						?>
					</table>
				</form>
			</div>
		</div>
	</div>	
</div>
<!-- End all user -->