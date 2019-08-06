<script>
	$(document).ready(function(){
		ajax_load_data('<?php echo base_url() ?>form/network/edit', '<?php echo base_url().ADMIN ?>network/network_edit/', '#myForm');		
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
				<?= tkt_form_number('rate',  $posts->p_rate, 'Network Rate (%)', '')?>
				<?= tkt_form_number('rate_network',  $posts->p_network_rate, 'Sub Network Rate (%)', '')?>
				<?= tkt_form_submit('mySubmit', 'Hoàn thành', 'btn-success', '')?>
			</form>
		</div>
	</div>
		
</div>
<!-- End add menu -->