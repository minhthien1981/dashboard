<?php $user_info = $this->session->userdata('login');?>
<div class="sidebar"><div class="sidebar-bg"></div>
<div class="myMenu">
	<ul>
		<?php if(Per_checkController('home', $user_info->u_point)):?>
			<li <?php echo isset($active) && $active == 'home' ? 'class="active"' : '' ?>>
				<h3><span class="fa fa-tachometer"></span><p>Dashboard</p></h3>
				<ul>
					<li><a <?php echo isset($active) && $active == 'home' && $subactive == 'lisense' ? 'class="active"' : '' ?> href="<?php echo base_url().ADMIN; ?>"><i class="fa fa-angle-right"></i>Home</a></li>
					<li><a <?php echo isset($subactive) && $active == 'home' && $subactive == 'myprofile' ? 'class="active"' : '' ?> href="<?php echo base_url().ADMIN; ?>home/myprofile/"><i class="fa fa-angle-right"></i>Thông tin cá nhân</a></li>
				</ul>
			</li>
		<?php endif;?>
		<?php if(Per_checkController('posts', $user_info->u_point)):?>
			<li <?php echo isset($active) && $active == 'posts' ? 'class="active"' : '' ?>>
				<h3><span class="fa fa-pencil-square-o"></span><p>Dữ liệu</p></h3>
				<ul>
					<?php if(Per_checkAction('posts', 'view',$user_info->u_point)):?>
						<li><a <?php echo isset($subactive) && $active == 'posts' && $subactive == 'posts_all' ? 'class="active"' : '' ?> href="<?php echo base_url().ADMIN; ?>posts/posts_all"><i class="fa fa-angle-right"></i>Truy xuất dữ liệu</a></li>
					<?php endif;?>
					<?php if(Per_checkAction('posts', 'add',$user_info->u_point)):?>
						<!-- <li><a <?php echo isset($subactive) && $active == 'posts' && ($subactive == 'posts_add') ? 'class="active"' : '' ?> href="<?php echo base_url().ADMIN; ?>posts/posts_add"><i class="fa fa-angle-right"></i>Thêm dữ liệu tổng</a></li> -->
						
						<li><a <?php echo isset($subactive) && $active == 'posts' && ($subactive == 'posts_add_channel') ? 'class="active"' : '' ?> href="<?php echo base_url().ADMIN; ?>posts/posts_add_channel"><i class="fa fa-angle-right"></i>Thêm Channel</a></li>
						<li><a <?php echo isset($subactive) && $active == 'posts' && ($subactive == 'posts_add_detail') ? 'class="active"' : '' ?> href="<?php echo base_url().ADMIN; ?>posts/posts_add_detail"><i class="fa fa-angle-right"></i>Thêm dữ liệu</a></li>
					<?php endif;?>
					<?php if(Per_checkAction('posts', 'edit',$user_info->u_point)):?>
						<li><a <?php echo isset($subactive) && $active == 'posts' && ($subactive == 'posts_edit' || $subactive == 'posts_detail') ? 'class="active"' : '' ?> href="#"><i class="fa fa-angle-right"></i>Chi tiết</a></li>
					<?php endif;?>
				</ul>
			</li>
		<?php endif;?>
		<?php if(Per_checkController('network', $user_info->u_point)):?>
		<li <?php echo isset($active) && $active == 'network' ? 'class="active"' : '' ?>>
			<h3><span class="fa fa-link"></span><p>Network</p></h3>
			<ul>
				<li><a <?php echo isset($subactive) && $active == 'network' && $subactive == 'network_all' ? 'class="active"' : '' ?> href="<?php echo base_url().ADMIN; ?>network/network_all/"><i class="fa fa-angle-right"></i>Tất cả</a></li>
				<li><a <?php echo isset($subactive) && $active == 'network' && $subactive == 'network_edit' ? 'class="active"' : '' ?> href="#"><i class="fa fa-angle-right"></i>Chi tiết</a></li>
			</ul>
		</li>
		<?php endif;?>
		<?php if(Per_checkController('user', $user_info->u_point)):?>
		<li <?php echo isset($active) && $active == 'user' ? 'class="active"' : '' ?>>
			<h3><span class="fa fa-users"></span><p>Quản trị viên</p></h3>
			<ul>
				<?php if(Per_checkAction('user', 'view',$user_info->u_point)):?>
					<li><a <?php echo isset($subactive) && $active == 'user' && $subactive == 'user_all' ? 'class="active"' : '' ?> href="<?php echo base_url().ADMIN; ?>user/user_all/"><i class="fa fa-angle-right"></i>Tất cả quản trị viên</a></li>
				<?php endif;?>
				<?php if(Per_checkAction('user', 'add',$user_info->u_point)):?>
					<li><a <?php echo isset($subactive) && $active == 'user' && $subactive == 'user_add' ? 'class="active"' : '' ?> href="<?php echo base_url().ADMIN; ?>user/user_add/"><i class="fa fa-angle-right"></i>Thêm quản trị viên</a></li>
				<?php endif;?>
				<?php if(Per_checkAction('user', 'edit',$user_info->u_point)):?>
					<li><a <?php echo isset($subactive) && $active == 'user' && $subactive == 'user_edit' ? 'class="active"' : '' ?> href="#"><i class="fa fa-angle-right"></i>Chi tiết</a></li>
				<?php endif;?>
			</ul>
		</li>
		<?php endif;?>
		<?php if(Per_checkController('setting', $user_info->u_point)):?>
		<li <?php echo isset($active) && $active == 'setting' ? 'class="active"' : '' ?>>
			<h3><span class="fa fa-cog"></span><p>Cài đặt</p></h3>
			<ul>
				<li><a <?php echo isset($subactive) && $active == 'setting' && $subactive == 'general' ? 'class="active"' : '' ?> href="<?php echo base_url().ADMIN; ?>setting/general"><i class="fa fa-angle-right"></i>Cài đặt chung</a></li>
				<li><a <?php echo isset($subactive) && $active == 'setting' && $subactive == 'logo' ? 'class="active"' : '' ?> href="<?php echo base_url().ADMIN; ?>setting/logo/"><i class="fa fa-angle-right"></i>Logo & Favicon</a></li>
				<li><a <?php echo isset($subactive) && $active == 'setting' && $subactive == 'network' ? 'class="active"' : '' ?> href="<?php echo base_url().ADMIN; ?>setting/network/"><i class="fa fa-angle-right"></i>Network</a></li>
			</ul>
		</li>
		<?php endif;?>
	</ul>
</div>
</div>