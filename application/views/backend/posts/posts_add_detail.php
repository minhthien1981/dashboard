<script>

	$(document).ready(function(e){
		var progressBar = document.getElementById("progressBar"),
		loadBtn = document.getElementById("mySubmit");

		function upload(data) {
			var xhr = new XMLHttpRequest();
			xhr.open("POST", "<?php echo base_url() ?>form/posts/add_detail", true);
			if (xhr.upload){
				xhr.upload.onprogress = function(e) {
					if (e.lengthComputable) {
						var loading =  Math.floor((e.loaded / e.total) * 100);
						if(loading > 99){loading = 99;}
						progressBar.innerHTML = loading + '%';
						progressBar.style.width = loading + '%';
					}
				}
				xhr.upload.onloadstart = function(e) {
					$("#result .msgsuccess").html('Đang xử lý ....');
					$("#result").show();
					progressBar.style.width = '0%';
					progressBar.innerHTML = '0%';
				}
				xhr.onloadend = function(e){
					progressBar.innerHTML = '100%';
					progressBar.style.width = '100%';
					loadBtn.disabled = false;
					loadBtn.innerHTML = 'Upload';
				}
				xhr.onload = function(){						
					var res = JSON.parse(xhr.responseText);
					console.log(res);
					$("#result span").html(res.message);
					$("#result").show();
					if(res.status == true){
						$('#uploadFile').replaceWith($('#uploadFile').val('').clone(true));
						$("#dtp_time").val('');
						$("#resultHtml table tbody").html('');

						$("#result span").removeClass("msgerror").addClass("msgsuccess");
						$("#result .msgsuccess").html('Upload file thành công !');
						var is_data = res.data.values;
						$.map(is_data, function(value, index) {								
							if(value[4] == 1){
								var checked = '';
							}else{
								var checked = 'checked';
							}
							$("#resultHtml table tbody").append('<tr data-id="'+value[3]+'"><th scope="row">'+(index +1)+'</th><td>'+value[0]+'</td><td>'+value[1]+'</td><td align="center">'+value[2]+'</td><td align="center"><span class="switch switch-sm"><input data-id="'+value[3]+'" '+checked+' type="checkbox" class="switch changeType" id="switch-sm'+value[3]+'"><label for="switch-sm'+value[3]+'"></label></span></td><td align="center"><a target="_blank" href="<?=base_url()?>posts/posts_detail/'+value[3]+'" class="btn btn-sm btn-success" type="button"><i class="fa fa-eye" aria-hidden="true"></i></a> <a target="_blank" href="<?=base_url()?>posts/posts_edit/'+value[3]+'" class="btn btn-sm btn-primary" type="button"><i class="fa fa-pencil" aria-hidden="true"></i></a></td></tr>');

						});
					}else{
						$("#result span").removeClass("msgsuccess").addClass("msgerror");
					}
					
				}
			}
			xhr.send(data);
		}

		loadBtn.addEventListener("click", function(e) {
			var myForm = document.getElementById('myForm');
			formData = new FormData(myForm);
			formData.append('vnTKT', $.cookie('myTKT'));
			formData.append('fileupload', $('#uploadBtn')[0].files[0]); 
			formData.append('fileupload2', $('#uploadBtn2')[0].files[0]); 
			this.disabled = true;
			this.innerHTML = "Uploading...";
			document.getElementById('progress').style.display = 'block';
			upload(formData);
		});

		$(document).on('click', ".changeType", function(){
			var id = $(this).attr("data-id");
			$.post('<?php echo base_url() ?>form/posts/type', {id: id, vnTKT: $.cookie('myTKT') }, function(res){
				if(res.success){
					console.log('done');
				}else{
					swal("Lỗi vui lòng thử lại!", "", "error");
				}
			}, 'json');
		});
	});


</script>
<style type="text/css">
	.field-custom-upload { margin: 0 0 5px 0; width: -moz-calc(100% - 100px); width: -webkit-calc(100% - 100px); width: calc(100% - 100px); font-size: 16px; float: left; padding: 7px 10px; border: none; -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, .07); box-shadow: inset 0 1px 2px rgba(0, 0, 0, .07); outline: 0; -webkit-transition: .05s border-color ease-in-out; transition: .05s border-color ease-in-out }
	.btn-custom-upload{
		float: left;
		width: 100px;
		font-size: 16px;
		padding: 7px 10px;
		font-weight: bold;
		text-align: center;
		background-color: #337AB7;
		color: #fff;
	}
	.uploadFile input.upload-input {
		position: absolute;
		top: 0;
		right: 0;
		margin: 0;
		padding: 0;
		font-size: 20px;
		cursor: pointer;
		opacity: 0;
		filter: alpha(opacity=0);
	}
</style>
<!-- Add menu -->
<div class="col-12">
	<form action="" method="post" name="myForm" id="myForm" style="position: relative">
		<div class="row">
			<div class="col-md-12">
				<h1 class="lee-title">Thêm dữ liệu mới</h1><div class="clearfix"></div>
				<div id="loading1" class="loading_wp_full"></div>
				<span id="result" style="display: none"><span class='msgsuccess'></span></span>
			</div>
			<div class="clearfix"></div>
			<div class="col-md-12 col-xs-12">
				<div class="option-content">
					<div class="row">
						<div class="col-md-12">
							<div style="display: none" id="progress" class="progress mgb10">
								<div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated">0%</div>
							</div>
						</div>
						<hr/>
						<div class="col-md-4 col-xs-12">
							<div class="row">
								<div class="col-12">
									<label class="title-field">Youtube Summary</span></label>
								</div>
								<div class="col-12">
									<div class="uploadFile">
										<input class="field-custom-upload" id="uploadFile" placeholder="File" disabled="disabled" />
										<div class="btn-custom-upload">
											<span>Chọn file</span>
											<input id="uploadBtn" type="file" class="upload-input" />
										</div>
										<script type="text/javascript">
											document.getElementById("uploadBtn").onchange = function () {
												document.getElementById("uploadFile").value = this.value;
											};
										</script>
									</div>
								</div>
							</div>							
						</div>
						<div class="col-md-4 col-xs-12">
							<div class="row">
								<div class="col-12">
									<label class="title-field">Youtube Red</span></label>
								</div>
								<div class="col-12">
									<div class="uploadFile">
										<input class="field-custom-upload" id="uploadFile2" placeholder="File" disabled="disabled" />
										<div class="btn-custom-upload">
											<span>Chọn file</span>
											<input id="uploadBtn2" type="file" class="upload-input" />
										</div>
										<script type="text/javascript">
											document.getElementById("uploadBtn2").onchange = function () {
												document.getElementById("uploadFile2").value = this.value;
											};
										</script>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-2 col-xs-12">
							<?= tkt_form_datetime('time', '', 'Month', 'MM-YYYY', '');?>
						</div>
						<div class="col-md-2 col-xs-12">
							<label class="title-field">Action</span></label>
							<button type="button" name="mySubmit" id="mySubmit" class=" w-100 btn btn-success pull-right">Upload</button>
						</div>
					</div>
				</div>
				<div id="resultHtml" class="option-content mgt10">
					<table class="table table-sm table-bordered table-striped">
					  	<thead class="thead-dark">
						    <tr>
						    	<th scope="col">#</th>
						      	<th scope="col">Channel Name</th>
						      	<th scope="col">Channel ID</th>
						      	<th scope="col">Doanh Thu</th>
						      	<th scope="col">Public</th>
						      	<th scope="col">Action</th>
						    </tr>
					  	</thead>
						<tbody>
							
						</tbody>
					</table>

				</div>	
			</div>
		</div>
	</form>
</div>
<!-- End add menu -->