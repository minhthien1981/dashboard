<script>
	$(document).ready(function(){
		ajax_load_data('<?php echo base_url() ?>form/posts/add_channel', '<?php echo base_url().ADMIN ?>posts/posts_edit/', '#myForm');

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
				<?= tkt_form_input('title',  '', 'Tên channel')?>				
				<?= tkt_form_input('channel',  '', 'Channel ID')?>
				<?= tkt_form_number('rate',  '', 'Network Rate (%)', '')?>
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
				<?= tkt_form_ckeditor('detail', '', 'Ghi chú')?>
				<?= tkt_form_submit('mySubmit', 'Hoàn thành', 'btn-success', '')?>
			</form>
		</div>
	</div>
		
</div>
<!-- End add menu -->