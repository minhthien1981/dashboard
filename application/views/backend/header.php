<html>
<head>
	<title><?php echo isset($title) ? $title : 'Admin' ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<link href="<?php echo base_url() ?>public/admin/img/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	
	<link rel="stylesheet" type="text/css" href="<?php echo public_url('bootstrap4/') ?>css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo public_url('bootstrap4/') ?>css/bootstrap-select.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo public_url('bootstrap4/') ?>date/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo public_url('admin/') ?>css/style.css">
	
	
	<script type="text/javascript" src="<?php echo public_url('admin/') ?>js/jquery-2.1.3.min.js"></script>
	<script type="text/javascript" src="<?php echo public_url('bootstrap4/') ?>js/popper.min.js"></script>
	<script type="text/javascript" src="<?php echo public_url('bootstrap4/') ?>js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo public_url('bootstrap4/') ?>js/bootstrap-select.min.js"></script>
	<script type="text/javascript" src="<?php echo public_url('bootstrap4/') ?>date/moment.js"></script>
	<script type="text/javascript" src="<?php echo public_url('bootstrap4/') ?>date/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="<?php echo public_url('admin/') ?>js/jquery-ui.1.11.1.js"></script>
	<script type="text/javascript" src="<?php echo public_url('admin/') ?>js/jquery.cookie.min.js"></script>
	<script type="text/javascript" src="<?php echo public_url('admin/') ?>js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="<?php echo public_url('admin/') ?>js/dataTables.bootstrap4.min.js"></script>
</head>
<div id="spinner"></div>
<body>
	<div class="container-fluid" style="position: relative;">
		<?php
			$user_info = $this->session->userdata('login');
		?>
		<div class="row">
			<div class="lee-header">
				<div class="header-home">
					<?php echo per_show($user_info->u_per);?>
					<a href="<?php echo base_url().ADMIN; ?>" ><?= $this->main_model->get_option('site_title')?></a>
				</div>
				<div class="hello-admin">
					<div class="menu">
						<ul>
							<li><div class="admin-page"><a href="<?php echo base_url().ADMIN ?>" title="Trang chủ">Click</a></div></li>
							<li><div class="reload-page"><a href="javascript:history.go(0)" title="Tải lại trang">Click</a></div></li>
						</ul>
					</div>
					<?php
						if(@getimagesize($user_info->u_img) !== false){
							$img_url = $user_info->u_img;
						}else{
							$img_url = base_url().'uploads/images/user/'.$user_info->u_img;
						}
					?>
					<div class="hello-admin-logout"><a href="<?php echo base_url().ADMIN ?>/login/logout"><i class="fa fa-power-off"></i>Logout</a></div>
					<a href="<?php echo base_url().ADMIN ?>home/myprofile/">
					<div class="hello-admin-images hidden-xs"><img src="<?= $img_url; ?>"></div>
					<div class="hello-admin-name hidden-xs"><a>Hello <?php echo $user_info->u_name;?> </a>&nbsp; - &nbsp;</div>
					</a>
				</div>
			</div>
		</div>
			