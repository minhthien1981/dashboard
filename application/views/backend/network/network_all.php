<?php $user_info = $this->session->userdata('login'); ?>
<script type="text/javascript">
	$(document).ready(function(){
		$('#myDataTable').DataTable( {
			"aoColumnDefs": [{ 'bSortable': false, 'aTargets': [ 0 ] }],
			"pageLength": 25,
			"order": [[ 0, "asc" ]],
			initComplete: function () {
	            this.api().columns(3).every( function () {
	            	var column = this;
	                $("#network").on( 'change', function () {
	                    var val = $.fn.dataTable.util.escapeRegex(
	                        $(this).val()
	                    );
	                    console.log(val);
	                    column.search( val ? '^'+val+'$' : '', true, false ).draw();
	                 } );
	 
	            } );
	        }
		});

		$(document).on('click', '#btnFilter', function(){
			var start = $("#dtp_startday").val();
			var end = $("#dtp_endday").val();
			window.open('<?= base_url().ADMIN?>network/network_all?start='+start+'&end='+end, '_self')
		});

	});	
</script>

<!-- All user -->
<div class="col-12">
	<div class="option-heading">
		<div class="arrow-up">+</div>
		<div class="arrow-down">-</div>
		<span>Tất cả channel</span>
	</div>
	<div class="option-content">
		<div class="row">
			<div class="action-heading col-12">
				<div class="row">
					<div class="col-md-6 col-sm-12">
						<div class="btn-group" role="group">
							<div class="btn-group" role="group">
								<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Action <i class="caret"></i>
								</button>
								<div class="dropdown-menu">
									
								</div>
							</div>							
							
							<?php
								$export = base_url().ADMIN.'network/export_excel_all/';
								if($start != '' && $end != ''){
									$export .='?start='.$start.'&end='.$end;
								}elseif($start != '' && $end == ''){
									$export .='?start='.$start;
								}
								elseif($start == '' && $end != ''){
									$export .='?end='.$end;
								}
							?>
							<a target="_blank" href="<?= $export ?>" name="exportExcel" id="exportExcel" class="btn btn-danger btn-sm"><i class="fa fa-file-excel-o"></i> Xuất Excel</a>
						</div>
					</div>
					<div class="col-md-2 col-sm-12">
						<div class="input-group">
							<span class="input-group-prepend">
								<span class="input-group-text">From</span>
							</span>
							<?php
								if($start == ''){
									$start = date('m-Y', time());
								}
							?>
							<input type="text" name="startday" id="dtp_startday" autocomplete="off" value="<?= $start ?>" class="form-control">
						</div>
						<script> $(document).ready(function(){ $("#dtp_startday").datetimepicker({format: "MM-YYYY"});});</script>
					</div>
					<div class="col-md-2 col-sm-12">
						<div class="input-group">
							<span class="input-group-prepend">
								<span class="input-group-text">To</span>
							</span>
							<?php
								if($end == ''){
									$end = date('m-Y', time());
								}
							?>
							<input type="text" name="endday" id="dtp_endday" autocomplete="off" value="<?= $end ?>" class="form-control">
						</div>
						<script> $(document).ready(function(){ $("#dtp_endday").datetimepicker({format: "MM-YYYY"});});</script>
					</div>
					<div class="col-md-2 col-sm-12">
						<button id="btnFilter" class="btn btn-info w-100">Lọc</button>
					</div>
				</div>
				<hr/>
				<div class="row">
					<div class="col-md-8 col-sm-12">
					</div>
					<div class="col-md-2 col-sm-12">
						<label for="channel" class="title-field pull-right">Network</label>
					</div>
					<div class="col-md-2 col-sm-12">
						<select  name="data[network]" id="network" class="selectpicker mgb5" data-live-search="true" show-tick data-width="100%" data-size="10">
							<option value="">None</option>
							<?php
								foreach ($network as $key => $item) {
									echo '<option value="'.$item->n_name.'">'.$item->n_name.'</option>';
								}		
							?>
						</select>
					</div>
				</div>
			</div>
			<div class="main-content col-12">
				<table id="myDataTable" class="display table table-striped table-bordered" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>Channel Name</th>
							<th>Channel ID</th>
							<th>Network</th>
							<th>Doanh Thu</th>
							<th>Rate</th>
							<th>DT Network</th>
							<th>DT Sub</th>
							<th>DT Channel</th>
							<th style="min-width: 70px;">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$total_doanhthu = 0;
							$total_network = 0;
							$total_subnet = 0;
							$total_channel = 0;

							foreach ($posts as $key => $item) {
								$total = $this->posts_model->total_doanhthu($item->p_id, $start, $end);

								if($item->p_rate != 0 && $total != 0){
									$dt_network = $total * (int)$item->p_rate / 100;
									$dt_subnet = $total * (int)$item->p_network_rate / 100;
									$dt_channel = $total - $dt_network;
									$dt_network = $dt_network - $dt_subnet;
								}else{
									$dt_subnet = 0;
									$dt_network = 0;
									$dt_channel = $total;
								}

								$network_text = '';
								$network_rate = '';
								foreach ($network as $n => $nw) {
									if($nw->n_id == $item->p_network){
										$network_text = $nw->n_name;
										$network_rate = $item->p_rate.'%';
									}
								}
								if($item->p_type == '1'){
									if($total != 0){
										$total_doanhthu += $total;
									}
									if($dt_network != 0){
										$total_network += $dt_network;
									}
									if($dt_subnet != 0){
										$total_subnet += $dt_subnet;
									}
									if($dt_channel != 0){
										$total_channel += $dt_channel;
									}
								}
								
								echo '<tr id="'.$item->p_id.'">';
								echo '<td align="center">'.$item->p_id.'</td>';
								
								echo '<td align="left"><a title="'.$item->p_title.'" href="'.base_url().ADMIN.'network/network_edit/'.$item->p_id.'">'.excerpt_words($item->p_title, 5).'</a></td>';

								echo '<td align="center"><a target="_blank" href="https://www.youtube.com/channel/UC'.$item->p_channel.'">'.$item->p_channel.'</a></div>';
								echo '<td align="center">'.$network_text.'</div>';
								echo '<td align="center">'.number_format((float)$total, 2, '.', ',').' </td>';
								echo '<td align="center">'.($item->p_rate - $item->p_network_rate).'-'.($item->p_network_rate).'-'.(100 -$item->p_rate).' </div>';
								echo '<td align="center">'.number_format((float)$dt_network, 2, '.', ',').'</div>';
								echo '<td align="center">'.number_format((float)$dt_subnet, 2, '.', ',').'</div>';
								echo '<td align="center">'.number_format((float)$dt_channel, 2, '.', ',').'</div>';

								echo  '<td align="center">';
									echo '<a href="'.base_url().ADMIN.'network/network_detail/'.$item->p_id.'" target="_blank" ><button class="btn btn-info btn-sm" type="button"><i class="fa fa-eye" aria-hidden="true"></i></button></a> ';
									echo '<a href="'.base_url().ADMIN.'network/network_edit/'.$item->p_id.'" ><button	class="btn btn-primary btn-sm" type="button"><i class="fa fa-pencil" aria-hidden="true"></i></button></a> ';
									
												
								echo '</td>';
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>	
	<div class="option-content">
		<table id="myDataTable" class="display table table-striped table-bordered" width="100%">
			<thead>
				<tr>
					<th>Tổng Doanh Thu</th>
					<th>Tổng Doanh Thu Network</th>
					<th>Tổng Doanh Thu Sub Network</th>
					<th>Tổng Doanh Thu Channel</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td align="center"><?= number_format((float)$total_doanhthu, 2, '.', ',') ?></td>
					<td align="center"><?= number_format((float)$total_network, 2, '.', ',') ?></td>
					<td align="center"><?= number_format((float)$total_subnet, 2, '.', ',') ?></td>
					<td align="center"><?= number_format((float)$total_channel, 2, '.', ',') ?></td>
					
				</tr>
			</tbody>
		</table>
	</div>
</div>
<!-- End all user -->