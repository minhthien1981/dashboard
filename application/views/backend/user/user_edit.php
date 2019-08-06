<script type="text/javascript">
$(document).ready(function(e){
	ajax_load_data('<?php echo base_url() ?>form/user/edit', '<?php echo base_url().ADMIN ?>user/user_edit/', '#myForm');
	ajax_load_data('<?php echo base_url() ?>form/user/resetpass_user', '<?php echo base_url().ADMIN ?>user/user_edit/', '#myForm2');
});
</script>
<!-- Edit User -->
<form action="" method="post" name="myForm" id="myForm" runat="server">
<div class="col-12">
	<h1 class="lee-title">Chỉnh sửa thông tin</h1><div class="clearfix"></div>
	<span id="result" style="display: none"><span class='msgerror'></span></span>
</div>
<div class="clearfix"></div>
<div class="col-12">
	<div class="row">
		<div class="col-md-6 col-xs-12">
			<?= form_hidden('data[postid]', $posts->u_id);?>
			<?= tkt_form_input('tennv', $posts->u_name, 'Tên<span> (*)</span>', 'Tên riêng sẽ hiển thị trên trang mạng của bạn')?>
			<?= tkt_form_input('usernv', $posts->u_user, 'Tên tài khoản<span> (*)</span>', 'Tài khoản dùng để đăng nhập hệ thống')?>
			<?= tkt_form_input('sdt', $posts->u_phone, 'Số điện thoại', '')?>
			<?= tkt_form_input('email', $posts->u_email, 'Email', '')?>
			<?= tkt_form_img('hinhanh', base_url().'uploads/images/user/'.$posts->u_img, 'Ảnh đại diện', '')?>
			<?= tkt_form_ckeditor('mota', $posts->u_detail, 'Mô tả', 'Có thể thêm giới tính thông tin hay một đoạn giới thiệu ngắn !')?>
		</div>
		<div class="col-md-6 col-xs-12">
			<div class="option-heading2">
				<span>Phân quyền theo chức vụ</span>
			</div>
			<div class="option-content">

				<?php if(in_array($posts->u_id, $this->config->item('super_admin'))): echo '<span class="col-red">Tài khoản có toàn quyền trong hệ thống !</span>'; else:?>		
					<?= per_radio($posts->u_per)?> 
				<?php endif; ?>
			</div>
			<div class="option-heading2 mgt10">
				<span>Phân quyền chi tiết</span>
			</div>
			<div class="option-content">
				<?php if(in_array($posts->u_id, $this->config->item('super_admin'))): echo '<span class="col-red">Tài khoản có toàn quyền trong hệ thống !</span>'; else:?>				
					<ul class="menu-checkbox-inline">
						<?php						
							$per_list = $this->config->item('per');
							foreach ($per_list as $key => $value){
								echo '<li class="checkbox checkbox-sm checkbox-warning "><i></i><input '.Per_checkBitwiseCheckbox($posts->u_point, $value['point']).' name="data[point][]" value="'.$value['point'].'" type="checkbox" id="checkbox'.$key.'"><label for="checkbox'.$key.'">'.$value['name'].'</label></li>';
								if(isset($value['view'])){
									echo '<li class="checkbox checkbox-info sub-menu-add"><i>——</i><input '.Per_checkBitwiseCheckbox($posts->u_point, $value['view']['point']).' name="data[point][]" value="'.$value['view']['point'].'" type="checkbox" id="checkboxview'.$key.'"><label for="checkboxview'.$key.'">'.$value['view']['name'].'</label></li>';
								}
								if(isset($value['add'])){
									echo '<li class="checkbox checkbox-success sub-menu-add"><i>——</i><input '.Per_checkBitwiseCheckbox($posts->u_point, $value['add']['point']).' name="data[point][]" value="'.$value['add']['point'].'" type="checkbox" id="checkboxadd'.$key.'"><label for="checkboxadd'.$key.'">'.$value['add']['name'].'</label></li>';
								}
								if(isset($value['edit'])){
									echo '<li class="checkbox checkbox-primary sub-menu-add"><i>——</i><input '.Per_checkBitwiseCheckbox($posts->u_point, $value['edit']['point']).' name="data[point][]" value="'.$value['edit']['point'].'" type="checkbox" id="checkboxedit'.$key.'"><label for="checkboxedit'.$key.'">'.$value['edit']['name'].'</label></li>';
								}
								if(isset($value['delete'])){
									echo '<li class="checkbox checkbox-danger sub-menu-add"><i>——</i><input '.Per_checkBitwiseCheckbox($posts->u_point, $value['delete']['point']).' name="data[point][]" value="'.$value['delete']['point'].'" type="checkbox" id="checkboxdelete'.$key.'"><label for="checkboxdelete'.$key.'">'.$value['delete']['name'].'</label></li>';
								}
								echo '<div class="clearfix"></div>';
							}
						?>										
					</ul>
				<?php endif; ?>
			</div>
			
			<div class="publish-post">
				<?= tkt_form_submit('mySubmit', 'Hoàn thành', 'btn-success pull-right', '')?>
			</div>
			</form>
			<?php if(in_array($posts->u_id, $this->config->item('super_admin'))): echo ''; else:?>
				<form action="" method="post" name="myForm2" id="myForm2" runat="server">
					<input type="hidden" name="data[postid]" value="<?php echo $posts->u_id; ?>">			
					<div class="option-heading mgt10">
						<div class="arrow-up">+</div>
						<div class="arrow-down">-</div>
						<span>Đổi mật khẩu</span>
					</div>
					<div class="option-content">
						<?= tkt_form_input('pass', '', 'Mật khẩu mới', '')?>
						<div class="publish-post2">
							<?= tkt_form_submit('mySubmit2', 'Reset', 'btn-primary pull-right', '')?>
						</div>
					</div>	
				</form>
			<?php endif; ?>
		</div>
	</div>
</div>

		
<!-- End edit user -->
