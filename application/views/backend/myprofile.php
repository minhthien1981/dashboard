<script type="text/javascript">
$(document).ready(function(e){
	ajax_load_data('<?php echo base_url() ?>form/my_profile', '<?php echo base_url().ADMIN ?>myprofile/', '#myprofile');
	ajax_load_data('<?php echo base_url() ?>form/my_profile_img', '<?php echo base_url().ADMIN ?>myprofile/', '#suaanh');
	ajax_load_data('<?php echo base_url() ?>form/my_profile_pass', '<?php echo base_url().ADMIN ?>myprofile/', '#doimatkhau');
});
</script>
<form action="" method="post" enctype="multipart/form-data" name="myprofile" id="myprofile" runat="server">
	<input type="hidden" name="data[postid]" value="<?php echo $data['post']['u_id'];?>">
	<div class="col-md-12"><span id="result" style="display: none"><span class='msgerror'></span></span></div>
	<div class="col-md-8 left">
		<div class="option-heading">
			<div class="arrow-up">+</div>
			<div class="arrow-down">-</div>
			<span>Thông tin cá nhân</span>
		</div>
		<div class="option-content">
			<div class="row">
				<div class="col-md-3"><p class="title-field">Tài khoản đăng nhập</p></div>
				<div class="col-md-9"><input class="field-custom-disable" name="data[username]" id="username" type="text" value="<?php echo $data['post']['u_name']?>" /></div>
			</div>
			<div class="row">
				<div class="col-md-3"><p class="title-field">Họ tên</p></div>
				<div class="col-md-9"><input class="field-custom" type="text" name="data[fullname]" id="fullname"  value="<?php echo $data['post']['u_fullname']?>" /></div>
			</div>
			<div class="row">
				<div class="col-md-3"><p class="title-field">Email</p></div>
				<div class="col-md-9"><input class="field-custom-disable" disabled="disabled" type="text" value="<?php echo $data['post']['u_email']?>" /></div>
			</div>
			<div class="row">
				<div class="col-md-3"><p class="title-field">Số điện thoại</p></div>
				<div class="col-md-9"><input class="field-custom" name="data[sdt]" id="sdt" type="text" value="<?php echo $data['post']['u_phone']?>" /></div>
			</div>
			<div class="row">
				<div class="col-md-3"><p class="title-field">Website</p></div>
				<div class="col-md-9"><input class="field-custom" name="data[link]" id="link" type="text" value="<?php echo $data['post']['u_link']?>" /></div>
			</div>
			<div class="row">
				<div class="col-md-3"><p class="title-field">Mô tả</p></div>
				<div class="col-md-9">
					<textarea class="field-custom" name="data[mota]" rows="4" type="text" id="mota" autocomplete="off"><?php echo $data['post']['u_detail'];?></textarea>
					<i class="note-field">Có thể thêm giới tính thông tin hay một đoạn giới thiệu ngắn !</i>
					<span id="mota_error" class="error"></span>
				</div>
			</div>
			<input name="chinhsua" type="submit" id="chinhsua" value="Hoàn tất" class="submitform myButton right"/>
		</div>
	</div>
</form>
<form action="" method="post" enctype="multipart/form-data" id="suaanh" name="suaanh" runat="server">
	<input type="hidden" name="data[postid]" value="<?php echo $data['post']['u_id'] ?>">
	<div class="col-md-4 col-xs-12 left">
		<!--
			Ảnh đại diện
		-->
		<div class="option-heading">
			<div class="arrow-up">-</div>
			<div class="arrow-down">+</div>
			<span>Ảnh đại diện</span>
		</div>
		<div class="option-content hide-content">
			<div class="col-md-12 col-xs-12">
					<div class="featured-image">
						<input class="imgInp" name="data[hinhanh]" type="file" />
						<img style="max-width: 100%;" class="this_img" src="<?php echo base_url().'uploads/images/user/'.$data['post']['u_img'] ?>" alt="Ảnh đại diện" />
					</div>
					<div class="image_icon">Click to upload !</div>
			</div>
			<div class="col-md-12 col-xs-12" style="margin-top: 10px;">
				<input name="changeimg" type="submit" id="changeimg" value="Hoàn tất" class="submitform myButton right"/><div class="loading_wp"></div>
			</div>
		</div>
	</div>
</form>
<form action="" method="post" enctype="multipart/form-data" id="doimatkhau" name="doimatkhau" runat="server">
	<div class="col-md-4 col-xs-12 left">
		<div class="option-heading mgt10">
			<div class="arrow-up">-</div>
			<div class="arrow-down">+</div>
			<span>Đỗi mật khẩu</span>
		</div>
		<div class="option-content hide-content">
			<div class="row">
				<div class="col-md-12"><p class="title-field">Mật khẩu cũ</p>
				<input class="field-custom" type="password" name="data[oldpass]" id="oldpass" placeholder="*****"  />
				<span id="oldpass_error" class="error"></span>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<p class="title-field">Mật khẩu mới</p>
					<input class="field-custom" type="password" name="data[newpass]" id="newpass" placeholder="*****"  />
					<span id="newpass_error" class="error"></span>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12"><p class="title-field">Nhập lại mật khẩu mới</p>
				<input class="field-custom" type="password" name="data[renewpass]" id="renewpass" placeholder="*****"  />
				<span id="renewpass_error" class="error"></span>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<input name="changepass" type="submit" id="changepass" value="Hoàn tất" class="myButton right"/>
				</div>
			</div>
		</div>
	</div>
</form>
<?php if($data['post']['u_type'] == '2'):?>
	<div class="col-md-12 col-xs-12 left">
		<div class="option-heading mgt10">
			<div class="arrow-up">-</div>
			<div class="arrow-down">+</div>
			<span>Thông tin tài khoản google +</span>
		</div>
		<div class="option-content hide-content">
			<div class="row">
				<div class="col-md-3"><p class="title-field">Google Name</p></div>
				<div class="col-md-9"><input class="field-custom-disable" disabled="disabled" type="text" value="<?php echo $data['post2']['google_name']?>" /></div>
			</div>
			<div class="row">
				<div class="col-md-3"><p class="title-field">Google Email</p></div>
				<div class="col-md-9"><input class="field-custom-disable" disabled="disabled" type="text" value="<?php echo $data['post2']['google_email']?>" /></div>
			</div>
			<div class="row">
				<div class="col-md-3"><p class="title-field">Google Link</p></div>
				<div class="col-md-9"><input class="field-custom-disable" disabled="disabled" type="text" value="<?php echo $data['post2']['google_link']?>" /></div>
			</div>
			<div class="row">
				<div class="col-md-3"><p class="title-field">Google Image</p></div>
				<div class="col-md-9"><img style="width: 100px; height:auto" src="<?php echo $data['post2']['google_picture_link'] ?>" alt="Ảnh đại diện" /></div>
			</div>
			
		</div>
	</div>
<?php endif;?>