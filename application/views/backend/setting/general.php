<script type="text/javascript">
$(document).ready(function(e){
	ajax_load_data('<?php echo base_url() ?>form/setting/general', '<?php echo base_url().ADMIN ?>setting/general/', '#myForm');
});
</script>
<div class="clearfix"></div>
<div class="col-3"></div>
<div class="col-6">
	<h1 class="lee-title">Cài đặt chung</h1><div class="clearfix"></div>
	<form action="" method="post" name="myForm" id="myForm" runat="server">
		<span id="result" style="display: none"><span class='msgerror'></span></span>
		<?= tkt_form_input('site_title', $this->main_model->get_option('site_title'), 'Tên trang web', '') ?>
		<?= tkt_form_input('tag_line', $this->main_model->get_option('tag_line'), 'Khẩu hiệu', '') ?>
		<?= tkt_form_input('meta_keywords', $this->main_model->get_option('meta_keywords'), 'Meta Keywords', '') ?>
		<?= tkt_form_input('meta_description', $this->main_model->get_option('meta_description'), 'Meta Description', '') ?>
		<?= tkt_form_input('about_us', $this->main_model->get_option('about_us'), 'Giới thiệu', '') ?>
		<?= tkt_form_input('email', $this->main_model->get_option('email'), 'Email', '') ?>
		<?= tkt_form_input('address', $this->main_model->get_option('address'), 'Địa chỉ', '') ?>
		<?= tkt_form_input('phone', $this->main_model->get_option('phone'), 'Số điện thoại', '') ?>
		<?= tkt_form_input('link_facebook', $this->main_model->get_option('link_facebook'), 'Facebook', '') ?>
		<?= tkt_form_input('link_skype', $this->main_model->get_option('link_skype'), 'Skype', '') ?>
		<?= tkt_form_input('link_twitter', $this->main_model->get_option('link_twitter'), 'Twitter', '') ?>
		<?= tkt_form_input('link_youtube', $this->main_model->get_option('link_youtube'), 'Youtube', '') ?>
		<?= tkt_form_input('link_google', $this->main_model->get_option('link_google'), 'Google+', '') ?>
		<?= tkt_form_input('link_flickr', $this->main_model->get_option('link_flickr'), 'Flickr', '') ?>
		<?= tkt_form_input('_analytics', $this->main_model->get_option('_analytics'), 'Google Analytics', '') ?>
		<?= tkt_form_submit('mySubmit', 'Lưu cài đặt', 'btn-success', '')?>
	</form>
</div>