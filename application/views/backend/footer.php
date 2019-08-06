
		</div>
		<div class="contents-footer">
			<p class="pull-left">Cảm ơn bạn đã sử dụng TKTAdmin</p>
			<p class="pull-right">TKTAdmin phiên bản <?php echo VERSION; ?></p>
		</div>
	</div>
	<!-- End content -->
	</div>
	<!-- End container-fluid -->

	<script type="text/javascript">
		var ckeditor_config = {
			base_url 		: '<?php echo base_url();?>',
			connector_path	: '<?php echo ADMIN;?>ckfinder/connector',
			html_path		: '<?php echo ADMIN;?>ckfinder/html'
		}
	</script>

	
	<script type="text/javascript" src="<?php echo public_url('documents/'); ?>ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="<?php echo public_url('documents/'); ?>ckfinder/ckfinder.js"></script>
	<script>
		CKFinder.setupCKEditor() ;
	</script>
	<script type="text/javascript" src="<?php echo public_url('admin/'); ?>js/sweet-alert.min.js"></script>
	<script type="text/javascript" src="<?php echo public_url('admin/'); ?>js/jquery-ui.1.11.1.js"></script>
	<script type="text/javascript" src="<?php echo public_url('admin/'); ?>js/alias.js"></script>
	<script type="text/javascript" src="<?php echo public_url('admin/'); ?>js/tags.js"></script>
	<script type="text/javascript" src="<?php echo public_url('admin/'); ?>js/main.js"></script>
	<script type="text/javascript" src="<?php echo public_url('admin/'); ?>js/ajax.js"></script>

</body>
</html>