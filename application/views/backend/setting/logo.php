<script type="text/javascript">
$(document).ready(function(e){
	$('[data-toggle="tooltip"]').tooltip();
	ajax_load_data('<?php echo base_url() ?>form/setting/logo', '<?php echo base_url().ADMIN ?>setting/logo/', '#myForm');
	ajax_load_data('<?php echo base_url() ?>form/setting/general', '<?php echo base_url().ADMIN ?>setting/logo/', '#myForm2');
});
</script>
	<div class="col-12">
		<h1 class="lee-title">Tùy chọn hiển thị</h1><div class="clearfix"></div>		
	</div>
	<div class="col-12">
		<div class="row">
			<div class="col-md-5 col-sm-12">
				<form action="" method="post" name="myForm" id="myForm" runat="server">
					<span id="result" style="display: none"><span class='msgerror'></span></span>
					<div class="option-content">
						<?= tkt_form_img('favicon', base_url().'uploads/images/'.$this->main_model->get_option('_favicon'), 'Favicon', '', ' style="width: 50px"')?>
						<hr/>
						<?= tkt_form_img('logo', base_url().'uploads/images/'.$this->main_model->get_option('_logo'), 'Logo', '')?>

						<div class="publish-post2">
							<?= tkt_form_submit('mySubmit', 'Cập nhật', 'btn-primary pull-right', '')?>
						</div>			
					</div>
				</form>
			</div>
		</div>
	</div>
	
	