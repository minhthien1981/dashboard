<script type="text/javascript">
$(document).ready(function(e){
	ajax_load_data('<?php echo base_url() ?>form/user/my_profile', '<?php echo base_url().ADMIN ?>home/myprofile/', '#myprofile');
	ajax_load_data('<?php echo base_url() ?>form/user/my_profile_img', '<?php echo base_url().ADMIN ?>home/myprofile/', '#suaanh');
	ajax_load_data('<?php echo base_url() ?>form/user/my_profile_pass', '<?php echo base_url().ADMIN ?>home/myprofile/', '#doimatkhau');
});
</script>

<div class="col-8">
	<form action="" method="post" name="myprofile" id="myprofile" runat="server">
		<?= form_hidden('data[postid]', $posts->u_id);?>
		<div class="row">
			<div class="col-md-12">
				<span id="result" style="display: none"><span class='msgerror'></span></span>
			</div>
		</div>
		<div class="clearfix"></div>		
		<div class="option-heading">
			<div class="arrow-up">+</div>
			<div class="arrow-down">-</div>
			<span>Thông tin cá nhân</span>
		</div>
		<div class="option-content">
			<?= tkt_form_input_row('user',  $posts->u_user, 'Tài khoản đăng nhập', '', array('class' => 'form-control field-custom-disable'))?>
			<?= tkt_form_input_row('name',  $posts->u_name, 'Họ tên')?>
			<?= tkt_form_input_row('email',  $posts->u_email, 'Email', '', array('class' => 'form-control field-custom-disable', 'disabled' => 'disabled'))?>
			<?= tkt_form_input_row('sdt',  $posts->u_phone, 'Số điện thoại')?>
			<?= tkt_form_input_row('link',  $posts->u_link, 'Website')?>
			<?= tkt_form_textarea_row('mota', $posts->u_detail, 'Mô tả', 'Có thể thêm giới tính thông tin hay một đoạn giới thiệu ngắn !')?>
			<div class="publish-post2">
				<?= tkt_form_submit('mySubmit', 'Hoàn thành', 'btn-success pull-right', '')?>
			</div>
		</div>
	</form>
</div>
<div class="col-4">
	<form action="" method="post" enctype="multipart/form-data" id="suaanh" name="suaanh" runat="server">
		<input type="hidden" name="data[postid]" value="<?php echo $posts->u_id ?>">
		
		<!-- Ảnh đại diện -->
		<div class="option-heading">
			<div class="arrow-up">-</div>
			<div class="arrow-down">+</div>
			<span>Ảnh đại diện</span>
		</div>
		<div class="option-content hide-content">
			<?= tkt_form_img('hinhanh', base_url().'uploads/images/user/'.$posts->u_img)?>
			<div class="publish-post2">
				<?= tkt_form_submit('mySubmit2', 'Hoàn thành', 'btn-success pull-right', '')?>
			</div>
		</div>
		
	</form>
	<form action="" method="post" enctype="multipart/form-data" id="doimatkhau" name="doimatkhau" runat="server">
		<div class="option-heading mgt10">
			<div class="arrow-up">-</div>
			<div class="arrow-down">+</div>
			<span>Đỗi mật khẩu</span>
		</div>
		<div class="option-content hide-content">
			<?= tkt_form_password('oldpass',  '', 'Mật khẩu cũ', '', ' placeholder="*****"')?>
			<?= tkt_form_password('newpass',  '', 'Mật khẩu mới', '', ' placeholder="*****"')?>
			<?= tkt_form_password('renewpass',  '', 'Nhập lại mật khẩu mới', '', ' placeholder="*****"')?>
			<div class="publish-post2">
				<?= tkt_form_submit('mySubmit3', 'Hoàn thành', 'btn-success pull-right', '')?>
			</div>
		</div>

	</form>
</div>