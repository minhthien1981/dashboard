<script>
	$(document).ready(function(){
		ajax_load_data('<?php echo base_url() ?>form/posts/edit', '<?php echo base_url().ADMIN ?>posts/posts_edit/', '#myForm');
		$('#dtp_time').datetimepicker({
			format: 'MM-YYYY'
		});

		$(document).on('click', '#addPostMeta', function(){
			var time = $("#dtp_time").val();
			var value = $("#value").val();
			if(time != '' && value != ''){
				$.post('<?php echo base_url() ?>form/posts/add_meta', {id: <?= $posts->p_id; ?> ,time: time, value: value, vnTKT: $.cookie('myTKT') }, function(res){
					if(res.success){
						//swal(res.message, "", "success");
						location.reload();
					}else{
						swal(res.message, "", "error");
					}
				}, 'json');
			}else{
				swal("", "Chưa điền đủ dữ liệu !", "error");
			}
		});

		$(document).on('click', '#addUserRelationships', function(){
			var value = $("#userList").val();
			if(value != 0){
				$.post('<?php echo base_url() ?>form/posts/add_relationships', {id: <?= $posts->p_id; ?> ,value: value, vnTKT: $.cookie('myTKT') }, function(res){
					if(res.success){
						//swal(res.message, "", "success");
						location.reload();
					}else{
						swal(res.message, "", "error");
					}
				}, 'json');
			}else{
				swal("", "Chưa chọn user !", "error");
			}
		});

		$(document).on('click', '.delete_item_relationshops', function(){
			var value = $(this).attr('data-id');
			var sele = $(this);
			if(value != 0){
				$.post('<?php echo base_url() ?>form/posts/delete_relationships', {id: <?= $posts->p_id; ?> ,value: value, vnTKT: $.cookie('myTKT') }, function(res){
					if(res.success){
						swal(res.message, "", "success");
						sele.parent().parent().remove();
					}else{
						swal(res.message, "", "error");
					}
				}, 'json');
			}else{
				swal("", "Chưa chọn user !", "error");
			}
		});
	});
</script>
<?php $user_info = $this->session->userdata('login'); ?>
<!-- Add menu -->
<div class="col-12">
	<div class="row">
		<div class="col-md-12">
			<h1 class="lee-title">Chỉnh sửa thông tin</h1><div class="clearfix"></div>	
		</div>
		<div class="clearfix"></div>
		
		<div class="col-md-6 col-xs-12">
			<form action="" method="post" name="myForm" id="myForm" style="position: relative">
				<div id="loading1" class="loading_wp_full"></div>
				<span id="result" style="display: none"><span class='msgerror'></span></span>
				<?= form_hidden('data[postid]', $posts->p_id);?>
				<?= tkt_form_input('title',  $posts->p_title, 'Tên channel')?>				
				<?= tkt_form_input('channel',  $posts->p_channel, 'Channel ID', '', 'disabled')?>
				<?= tkt_form_number('rate',  $posts->p_rate, 'Network Rate (%)', '')?>

				<label for="channel" class="title-field">Network</label>
				<select  name="data[network]" id="network" class="selectpicker mgb5" data-live-search="true" show-tick data-width="100%" data-size="10">
					<option value="0">None</option>
					<?php
						foreach ($network as $key => $item) {
							if($item->n_id == $posts->p_network){
								echo '<option selected value="'.$item->n_id.'">'.$item->n_name.'</option>';
							}else{
								echo '<option value="'.$item->n_id.'">'.$item->n_name.'</option>';
							}
						}		
					?>
				</select>

				<div class="option-heading2 mgt10">
					<span>Vùng miền</span>
				</div>
				<?php 
					$arr =  array();
					$arr = explode(",", $posts->p_zone);
				?>
				<div class="option-content">
					<ul class="menu-checkbox-inline">
						<li class="checkbox checkbox-sm checkbox-info sub-menu-add" style="margin-right: 20px" >
							<input type="checkbox" id="inlineCheckbox1" value="Bắc" name="data[zone][]"
							<?php foreach ($arr as $key => $value) {
								echo $value == "Bắc"? "checked=checked" : "";
							}
							?>
							>
							<label for="inlineCheckbox1"> Bắc </label>
						</li>
						<li class="checkbox checkbox-sm checkbox-info sub-menu-add" style="margin-right: 20px"d>
							<input type="checkbox" id="inlineCheckbox2" value="Trung" name="data[zone][]"
								<?php foreach ($arr as $key => $value) {
									echo $value == "Trung"? "checked=checked" : "";
								}
							?>
							>
							<label for="inlineCheckbox2"> Trung </label>
						</li>
						<li class="checkbox checkbox-sm checkbox-info sub-menu-add" >
							<input type="checkbox" id="inlineCheckbox3" value="Nam" name="data[zone][]"
							<?php foreach ($arr as $key => $value) {
									echo $value == "Nam"? "checked=checked" : "";
								}
							?>>
							<label for="inlineCheckbox3"> Nam </label>
						</li>
					</ul>
				</div>


				<div class="option-heading2 mgt10">
					<span>Danh mục</span>
				</div>
				<?php 
					$arr =  array();
					$arr = explode(",", $posts->p_cat);

					$arr_cat = array("Film VN","Film Nước ngoài","Comedy","Celebrities","KOLs","Music","Vlog","Food","Travel","Beauty","Fashion","Kids","Motor/Automobile","Gaming","Fitness","DIY ","Sport ","News","Review","Tech","Education","Entertainment","Cover","Underground/Indie","Dance ","Audio book","Tử vi ","Sinh tồn - săn bắt - hái lượm","Slime","Horror ","Tôn giáo","Bolero/Cải lương");
				?>

				<div class="option-content">
					<ul class="menu-checkbox-inline">
					<?php foreach ($arr_cat as $key_cat => $value_cat ) { ?>
						<li class="checkbox checkbox-sm checkbox-info sub-menu-add" style="margin-right: 20px" >
							<input type="checkbox" id="<?php echo $key_cat ?>" value="<?php echo $value_cat ?>" name="data[cat][]"
							<?php foreach ($arr as $key => $value) {
								echo $value == $value_cat ? "checked=checked" : "";
							}
							?>
							>
							<label for="<?php echo $key_cat ?>"> <?php echo $value_cat ?></label>
						</li>

					<?php } ?>
					</ul>
				</div>


				<?= tkt_form_ckeditor('detail', $posts->p_detail, 'Ghi chú')?>
				<?= tkt_form_submit('mySubmit', 'Hoàn thành', 'btn-success', '')?>
			</form>
		</div>
		
		<div class="col-md-6 col-xs-12">
			<div class="option-heading2">
				<span>Thông tin doanh thu</span>
			</div>
			<div class="option-content">
				<table id="tableDoanhThu" class="display table table-striped table-bordered" width="100%">
					<thead>
						<tr>
							<th>Tháng</th>
							<th>Doanh Thu</th>
							<th>DT Channel</th>
							<th>DT Network</th>
							<?php if((int)$user_info->u_per < 2): ?>
								<th>Action</th>
							<?php endif;?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($post_meta as $key => $value):
								if($posts->p_rate != 0 && $value->pm_value != 0){
									$dt_network = $value->pm_value * (int)$posts->p_rate / 100;
									$dt_channel = $value->pm_value - $dt_network;
								}else{
									$dt_network = 0;
									$dt_channel = $value->pm_value;
								}
							?>
							<tr id="<?= $value->pm_id; ?>">
								<td align="center"><?= date('m-Y', strtotime($value->pm_time))?></td>
								<td align="center"><?= number_format((float)$value->pm_value, 2, ',', '.')?></td>
								<td align="center"><?= number_format((float)$dt_channel, 2, ',', '.')?></td>
								<td align="center"><?= number_format((float)$dt_network, 2, ',', '.')?></td>
								<?php if((int)$user_info->u_per < 2): ?>
								<td align="center"><button type="button" id="<?= $value->pm_id;?>" class="delete_item_table btn btn-danger btn-sm" onclick="del(<?= $value->pm_id;?>, '<?= base_url() ?>form/posts/delete_meta')"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
								<?php endif;?>
							</tr>
						<?php endforeach; ?>
						<?php if((int)$user_info->u_per < 2): ?>
						<tr><td colspan="5"><h2 class="title-field">Thêm mới</h2></td></tr>
						<tr>
							<td>
								<div class="input-group">
									<span class="input-group-prepend">
										<div class="input-group-text bg-transparent"><i class="fa fa-calendar"></i></div>
									</span>
									<input type="text" name="data[time]" id="dtp_time" autocomplete="off" value="" class="form-control"/>
								</div>
							</td>
							<td>
								<input type="number" name="data[value]" value="" id="value" class="form-control" autocomplete="off">
							</td>
							<td></td>
							<td></td>
							<td align="center">
								<button type="button" id="addPostMeta" class="delete_item_table btn btn-success btn-sm">
									<i class="fa fa-plus" aria-hidden="true"></i>
								</button></td>
						</tr>
						<?php endif;?>
					</tbody>
				</table>

			</div>
			<div class="option-heading2 mgt10">
				<span>User quản lý</span>
			</div>
			<div class="option-content">
				<table id="tableDoanhThu" class="display table table-striped table-bordered" width="100%">
					<thead>
						<tr>
							<th>User</th>
							<th>Role</th>
							<?php if((int)$user_info->u_per < 4): ?>
							<th>Action</th>
							<?php endif;?>
						</tr>
					</thead>
					<tbody>
						<?php if($relationships): ?>
						<?php foreach ($relationships as $key => $value): ?>
							<tr id="<?= $value->target_id; ?>">
								<td align="center"><?= $value->u_name?></td>
								<td align="center"><?= per_show($value->u_per) ?></td>
								<?php if((int)$user_info->u_per < 4): ?>
								<td align="center"><button type="button" data-id="<?= $value->target_id;?>" class="delete_item_relationshops btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></button></td>
								<?php endif;?>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
						<?php if((int)$user_info->u_per < 4): ?>
						<tr><td colspan="3"><h2 class="title-field">Thêm mới</h2></td></tr>
						<tr>
							<td colspan="2">
								<select class="form-control selectpicker"  name="userList" id="userList" data-live-search="true" show-tick data-width="100%" data-size="10">
								<option value="0">None</option>
								<?php echo isset($user_select) ? $user_select : ''; ?>			
							</select>
							</td>
							<td align="center">
								<button type="button" id="addUserRelationships" class="btn btn-success btn-sm">
									<i class="fa fa-plus" aria-hidden="true"></i>
								</button></td>
						</tr>
						<?php endif;?>
					</tbody>
				</table>
			</div>
		</div>
		
	</div>
		
</div>
<!-- End add menu -->