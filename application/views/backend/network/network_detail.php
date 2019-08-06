<script>
	$(document).ready(function(){
		ajax_load_data('<?php echo base_url() ?>form/posts/edit', '<?php echo base_url().ADMIN ?>posts/posts_edit/', '#myForm');

	});
</script>
<?php $user_info = $this->session->userdata('login'); ?>
<!-- Add menu -->
<div class="col-12">
	<div class="row">
		<div class="col-md-12">
			<h1 class="lee-title">Thông tin chi tiết</h1><div class="clearfix"></div>	
		</div>
		<div class="clearfix"></div>
		
		<div class="col-md-12 col-xs-12">
			<div class="option-content">
				<label class="title-field">Tên channel: <?= $posts->p_title;?></label><br/>
				<label class="title-field">Channel ID: <?= $posts->p_channel;?></label><br/>
				<label class="title-field">Rate Network: <?= $posts->p_rate - $posts->p_network_rate;?>%</label><br/>
				<label class="title-field mgb20">Rate Sub Network: <?= $posts->p_network_rate;?>%</label>
				<table id="tableDoanhThu" class="display table table-striped table-bordered" width="100%">
					<thead>
						<tr>
							<th>Tháng</th>
							<th>Doanh Thu</th>
							<th>Doanh Thu Network</th>
							<th>Doanh Thu Sub Network</th>
							<th>Doanh Thu Channel</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$total = 0;
							foreach ($post_meta as $key => $value):
								$total += $value->pm_value;
								if($posts->p_rate != 0 && $value->pm_value != 0){
									$dt_network = $value->pm_value * (int)$posts->p_rate / 100;
									$dt_subnet = $value->pm_value * (int)$posts->p_network_rate / 100;
									$dt_channel = $value->pm_value - $dt_network;
									$dt_network = $dt_network - $dt_subnet;
								}else{
									$dt_network = 0;
									$dt_channel = $value->pm_value;
									$dt_subnet = 0;
								}


								?>
							<tr id="<?= $value->pm_id; ?>">
								<td align="center"><?= date('m-Y', strtotime($value->pm_time))?></td>
								<td align="center"><?= number_format((float)$value->pm_value, 2, ',', '.')?></td>
								<td align="center"><?= number_format((float)$dt_network, 2, ',', '.')?></td>
								<td align="center"><?= number_format((float)$dt_subnet, 2, ',', '.')?></td>
								<td align="center"><?= number_format((float)$dt_channel, 2, ',', '.')?></td>
							</tr>
						<?php endforeach; ?>
						<tr>
							<?php
								@$total_network = $total * (int)$posts->p_rate / 100;
								@$total_subnet = $total * (int)$posts->p_network_rate / 100;
								@$total_channel = $total - $total_network;
								@$total_network = $total_network - $total_subnet;
							?>
							<td align="center"><strong>Tổng</strong></td>
							<td align="center"><strong><?= number_format((float)$total, 2, ',', '.')?></strong></td>
							<td align="center"><strong><?= number_format((float)$total_network, 2, ',', '.')?></strong></td>
							<td align="center"><strong><?= number_format((float)$total_subnet, 2, ',', '.')?></strong></td>
							<td align="center"><strong><?= number_format((float)$total_channel, 2, ',', '.')?></strong></td>
						</tr>
					</tbody>
				</table>
				<a type="button" target="_blank" href="<?php echo base_url().ADMIN.'network/export_excel/'.$posts->p_id; ?>" name="exportExcel" id="exportExcel" class="btn btn-success">Xuất Excel</a>
			</div>
		</div>
	</div>
		
</div>
<!-- End add menu -->