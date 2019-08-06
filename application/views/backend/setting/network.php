<script type="text/javascript">
$(document).ready(function(e){

	ajax_load_data('<?php echo base_url() ?>form/setting/network_add', '<?php echo base_url() ?>setting/network/', '#myForm');
	ajax_load_data('<?php echo base_url() ?>form/setting/network_edit', '<?php echo base_url() ?>setting/network/', '#editrow', '#result2', '.loading_wp_full');

	$('#myTable').dataTable({
		"aoColumnDefs": [{ 'bSortable': false, 'aTargets': [ 3 ] }],
		"pageLength": 25,
		"order": []
	});

	$(document).on('click', '.editrow', function(){
        $('#editId').val($(this).attr('data-id'));
        $('#editName').val($(this).attr('data-name'));
        $('#editOrder').val($(this).attr('data-order'));
    });
});
</script>

<div class="col-12">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="col-12">
				<h1 class="lee-title">Network</h1><div class="clearfix"></div>		
			</div>
			<form action="" method="post" name="myForm" id="myForm" runat="server">
				<span id="result" style="display: none"><span class='msgerror'></span></span>
				<div class="option-content">
					<?= tkt_form_input('name',  '', 'Tên')?>
					<?= tkt_form_number('order',  '', 'Vị trí', '')?>
					<div class="publish-post2">
						<?= tkt_form_submit('mySubmit', 'Cập nhật', 'btn-success', '')?>
					</div>			
				</div>
			</form>
		</div>

		<div class="col-md-6 col-sm-12">
			<div class="option-content">
				<table id="myTable" class="display table table-striped table-bordered" width="100%">
					<thead>
					<tr>
						<th>ID</th>
						<th>Tên</th>
						<th>Vị trí</th>
						<th>Hành động</th>
					</tr>
					</thead>
			        <?php
			            foreach ($posts as $key => $item) {
			                $html = '';
			                $html .= '<tr id="'.$item->n_id.'">';
			                    $html .= '<td align="center">'.$item->n_id.'</td>';
			                    $html .= '<td align="left">'.$item->n_name.'</td>';
			                    $html .= '<td align="center">'.$item->n_order.'</td>';
			                    $html .= '<td align="center"><button class="btn btn-primary btn-sm editrow" data-id="'.$item->n_id.'" data-name="'.$item->n_name.'" data-order="'.$item->n_order.'" data-toggle="modal" data-target="#myModal" type="button"><i class="fa fa-pencil" aria-hidden="true"></i></button>
			                            <button type="button" id="'.$item->n_id.'" class="delete_item_table btn btn-danger btn-sm" onclick="del('.$item->n_id.', \''.base_url().'form/setting/network_delete'.'\')"><i class="fa fa-trash" aria-hidden="true"></i></button>';
			                $html .= '</tr>';
			                echo $html;
			            }   
			        ?>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Modal title</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
			</div>
			<div class="modal-body">
				<div id="modal_form_login" class="modal_fomr_login">
					<form id="editrow" style="position: relative;">
                        <input type="hidden" id="editId" name="data[postid]" value="0">
                        <span id="result2" style="display: none"><span class='msgerror'></span></span>
						<div class="form-group">
							<label for="name" class="title-field">Tên</label>
							<input type="text" name="data[name]" value="" id="editName" class="form-control" autocomplete="off">
						</div>
						<div class="form-group">
							<label for="order" class="title-field">Vị trí</label>
							<input type="number" name="data[order]" value="" id="editOrder" class="form-control" autocomplete="off">
						</div>
                        <div class="loading_wp_full"></div>     
						<?= tkt_form_submit('editSubmit', 'Cập nhật', 'btn-success w-100', '')?>
						<div class="clearfix"></div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>