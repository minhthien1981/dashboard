<script type="text/javascript">
$(document).ready(function(e){
	ajax_load_data('<?php echo base_url() ?>form/user/add', '<?php echo base_url().ADMIN ?>user/user_edit/', '#myForm');
});
</script>
<!-- Add User -->
<form action="" method="post" name="myForm" id="myForm" runat="server">
	<div class="col-12">
		<h1 class="lee-title">Thêm quản trị viên mới</h1><div class="clearfix"></div>
		<span id="result" style="display: none"><span class='msgerror'></span></span>
	</div>
	<div class="clearfix"></div>
	<div class="col-12">
		<div class="row">
			<div class="col-md-6 col-sm-12">
				<?= tkt_form_input('tennv', '', 'Tên<span> (*)</span>', 'Tên riêng sẽ hiển thị trên trang mạng của bạn')?>
				<?= tkt_form_input('usernv', '', 'Tên tài khoản<span> (*)</span>', 'Tài khoản dùng để đăng nhập hệ thống')?>
				<?= tkt_form_input('pass', '', 'Mật khẩu<span> (*)</span>', '')?>
				<?= tkt_form_input('sdt', '', 'Số điện thoại', '')?>
				<?= tkt_form_input('email', '', 'Email', '')?>
				<?= tkt_form_img('hinhanh', base_url().'uploads/images/user/default.png', 'Ảnh đại diện', '')?>
				<?= tkt_form_ckeditor('mota', '', 'Mô tả', 'Có thể thêm giới tính thông tin hay một đoạn giới thiệu ngắn !')?>				
			</div>
			<div class="col-md-6 col-sm-12">			
				<div class="option-heading2">
					<span>Phân quyền theo chức vụ</span>
				</div>
				<div class="option-content">
					<?= per_radio() ?>
				</div>
				<div class="option-heading2 mgt10">
					<span>Phân quyền chi tiết</span>
				</div>
				<div class="option-content">
					<ul class="menu-checkbox-inline">
						<?php
							$per_list = $this->config->item('per');
							foreach ($per_list as $key => $value){
								echo '<li class="checkbox checkbox-sm checkbox-warning"><i></i><input name="data[point][]" value="'.$value['point'].'" type="checkbox" id="checkbox'.$key.'" ><label for="checkbox'.$key.'" class="inputlevel0">'.$value['name'].'</label></li>';
								if(isset($value['view'])){
									echo '<li class="checkbox checkbox-info sub-menu-add"><i>——</i><input name="data[point][]" value="'.$value['view']['point'].'" type="checkbox" id="checkboxview'.$key.'"><label for="checkboxview'.$key.'">'.$value['view']['name'].'</label></li>';
								}
								if(isset($value['add'])){
									echo '<li class="checkbox checkbox-success sub-menu-add"><i>——</i><input name="data[point][]" value="'.$value['add']['point'].'" type="checkbox" id="checkboxadd'.$key.'" ><label for="checkboxadd'.$key.'">'.$value['add']['name'].'</label></li>';
								}
								if(isset($value['edit'])){
									echo '<li class="checkbox checkbox-primary sub-menu-add"><i>——</i><input name="data[point][]" value="'.$value['edit']['point'].'" type="checkbox" id="checkboxedit'.$key.'"><label for="checkboxedit'.$key.'">'.$value['edit']['name'].'</label></li>';
								}
								if(isset($value['delete'])){
									echo '<li class="checkbox checkbox-danger sub-menu-add"><i>——</i><input name="data[point][]" value="'.$value['delete']['point'].'" type="checkbox" id="checkboxdelete'.$key.'"><label for="checkboxdelete'.$key.'">'.$value['delete']['name'].'</label></li>';
								}
								echo '<div class="clearfix"></div>';
							}
						?>											
					</ul>
					
				</div>

				
				<div class="publish-post">
					<?= tkt_form_submit('mySubmit', 'Hoàn thành', 'btn-success pull-right', '')?>
				</div>
			</div>
		</div>
	</div>
</form>
<!-- End add user -->