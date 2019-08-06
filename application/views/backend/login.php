<!DOCTYPE html>
<html lang="vi">
<head>
	<title><?= $title ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="<?php echo base_url() ?>uploads/images/<?php echo $this->main_model->get_option('_favicon')?>"/>
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo public_url('login/') ?>fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo public_url('login/') ?>css/style.css">
	<!--===============================================================================================-->
</head>
<body>
	<canvas id="playArea"></canvas>
	<div class="container-wp">
		<div class="container">
			<div class="wrap-login">
				<?= form_open(ADMIN.'login', 'class="login-form validate-form" name="dangnhap" id="myform"');?>
					<span class="login-form-logo">
						<img src="<?= base_url() ?>uploads/images/<?= $this->main_model->get_option('_logo')?>" alt="Logo">
					</span>
					<span class="login-form-title">Login</span>
					<div class="wrap-input validate-input" data-validate="<?= form_error('username') != '' ? form_error('username') : 'Username is reauired'; ?>" >
						<span class="label-input">Username</span>
						<input class="input" type="text" name="username" value="<?= isset($username) ? htmlspecialchars($username) : ''; ?>"  placeholder="Type your username">
						<span class="focus-input" data-symbol="&#xf007;"></span>
					</div>
					<div class="wrap-input validate-input" data-validate="<?= form_error('password') != '' ? form_error('password') : 'Password is reauired'; ?>">
						<span class="label-input">Password</span>
						<input class="input" type="password" name="password" placeholder="Type your password">
						<span class="focus-input" data-symbol="&#xf023;"></span>
					</div>
					<div class="txt1 text-right pdb10">
						<a href="#">Forgot password?</a>
					</div>
					<div class="colred txt1 pdb10 pdt10">
						<span class="colred"><?= form_error('login'); ?></span>
					</div>
					<div class="container-login-form-btn">
						<div class="wrap-login-form-btn">
							<div class="login-form-bgbtn"></div>
							<button type="submit" name="login" class="login-form-btn">Login</button>
							<input type="hidden" name="redirect" value="<?php if(isset($_GET['redirect'])) echo $_GET['redirect'];?>">
						</div>
					</div>
					<div class="txt1 text-center pdt20 pdb20">
						<span>
							Or Sign Up Using
						</span>
					</div>
					<div class="login-social-wp">
						<a href="#" class="login-social-item bgfb hidden">
							<i class="fa fa-facebook"></i>
						</a>
						<a href="#" class="login-social-item bgtw hidden">
							<i class="fa fa-twitter"></i>
						</a>
						<a href="<?php base_url();?>login/with_google/" class="login-social-item bggp">
							<i class="fa fa-google"></i>
						</a>
					</div>
					<div class="signup-wp pdt20 hidden">
						<span class="txt1 pdb10">
							New here? Create an your account
						</span>
						<a href="signup.html" class="txt2">
							Sign Up Now
						</a>
					</div>
				<?= form_close();?>
				<div class="login-footer">
					<p class="left"><?= $this->main_model->get_option('site_title')?></p>
					<p class="right">TKT Admin Version <?= VERSION ?></p>
				</div>
			</div>
		</div>
	</div>
	

	<!--===============================================================================================-->
	<script src="<?php echo public_url('login/') ?>js/jquery-3.2.1.min.js"></script>
	<script src="<?php echo public_url('login/') ?>js/main.js"></script>
	
</body>
</html>