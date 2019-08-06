function del(id, url_post, text = '', text_success = ''){
	swal({
		title: "Bạn chắc chắn xóa?",
		text: text,
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: "btn-danger",
		confirmButtonText: "Có, Tôi muốn xóa!",
		cancelButtonText: "Không!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
	function(isConfirm) {
		if (isConfirm) {
			var pos = $.post(url_post, { vnTKT: $.cookie('myTKT'), delete_post: id } );
			pos.done(function(data) {
				if(data == ''){
					$("tr#"+id).hide();
					swal("Xóa thành công!", "", "success");
				}else{
					swal(data, "", "error");
				}
				
			});
			
		} else {
			swal("Đã hủy xóa", "", "error");
		}
	});
}

function delItem(id, url_post, url_redirect, text = '', text_success = ''){
	swal({
		title: "Bạn chắc chắn xóa?",
		text: text,
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: "btn-danger",
		confirmButtonText: "Có, Tôi muốn xóa!",
		cancelButtonText: "Không!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
	function(isConfirm) {
		if (isConfirm) {
			var pos = $.post(url_post, { vnTKT: $.cookie('myTKT'), delete_post: id } );
			pos.done(function(data) {
				if(data == ''){					
					swal("Xóa thành công!", "", "success");
					location.href = url_redirect;
				}else{
					swal(data, "", "error");
				}
				
			});
			
		} else {
			swal("Đã hủy xóa", "", "error");
		}
	});
}

function delAll(url_post, text = ''){
	var ck = 0;
	var iarr = new Array();
	icheck = document.getElementsByName('ick[]');
	for(i = 0; i < icheck.length; i++){
		if(icheck[i].checked == true){
			ck = 1;
		}
	}
	if(ck == 0){
		swal("", "Vui lòng chọn tin muốn xóa !", "error");
		return false;
	}
	else{
		for(i = 0; i < icheck.length; i++){
			if(icheck[i].checked == true){
				vItems = icheck[i].value;
				iarr.push(vItems);
			}
		}
		listItemForDelete = iarr.join(',');
		if(listItemForDelete){
			
			swal({
				title: "Bạn chắc chắn xóa tất cả những gì đã chọn?",
				text: text,
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-danger",
				confirmButtonText: "Có, Xóa hết!",
				cancelButtonText: "Không!",
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function(isConfirm) {
				if (isConfirm) {
					var pos = $.post(url_post, { vnTKT: $.cookie('myTKT'), delete_multi_post: listItemForDelete } );
					pos.done(function(data){
						$('input.check_items:checked').each(function(){
							$(this).parent().parent().parent().hide();
						});
					});
					swal("Xóa thành công!", "", "success");
				} else {
				swal("Đã hủy xóa", "", "error");
				}
			});
		}
	}
}
/**
 * [ajax_load_data description]
 * @param  {[type]} url_post   [url cần tới]
 * @param  {[type]} url_direct [url trả về]
 * @param  {[type]} idform     [id của form]
 * @return {[type]}            [description]
 */
function ajax_load_data(url_post, url_direct, idform, idresult = '#result', classload = '.loading_wp_over') {
	$(idform).on('submit', function(e){
		e.preventDefault();
		for (instance in CKEDITOR.instances) {
			CKEDITOR.instances[instance].updateElement();
		}
		var loading = $(classload);
		var myformData = new FormData(this);
		myformData.append('vnTKT', $.cookie('myTKT'));
		$.ajax({
			type: 'post',
			url: url_post,
			dataType : 'html',
			data: myformData,
			contentType: false,
			cache: false,
			processData: false,
			beforeSend: function(){
				loading.show();
			},
			complete: function(){
				loading.hide();
			},
			success: function(html){
				if(!isNaN(html)) {
					location.href = url_direct + html;
				} else {
					$(idresult +" .msgerror").html(html);
					$(idresult).show();
				}
			},
			error: function(){
				alert('Error please try again');
			}
		});
	});
	$(idform).submit(function(e){return false;});
}
/**
 * [ajax_load_data description]
 * @param  {[type]} url_post   [description]
 * @param  {[type]} url_direct [description]
 * @param  {[type]} idform     [description]
 * @return {[type]}            [description]
 */
function ajax_load_data_html(url_post, idresult, idform) {
	$(idform).on('submit', function(e){
		e.preventDefault();
		for (instance in CKEDITOR.instances) {
			CKEDITOR.instances[instance].updateElement();
		}
		var loading = $(".loading_wp");
		var myformData = new FormData(this);
		myformData.append('vnTKT', $.cookie('myTKT'));
		$.ajax({
			type: 'post',
			url: url_post,
			dataType : 'html',
			data: myformData,
			contentType: false,
			cache: false,
			processData: false,
			beforeSend: function(){
				loading.show();
			},
			complete: function(){
				loading.hide();
			},
			success: function(html){
				$(idresult).html(html);
			},
			error: function(){
				alert('Error please try again');
			}
		});
	});
	$(idform).submit(function(e){return false;});
}
/**
 * [ajax_update description]
 * @param  {[type]} url_post   [description]
 * @param  {[type]} url_direct [description]
 * @param  {[type]} idform     [description]
 * @return {[type]}            [description]
 */
function ajax_update(url_post, url_direct, idform) {
	$(idform).on('submit', function(e){
		e.preventDefault();
		for (instance in CKEDITOR.instances) {
			CKEDITOR.instances[instance].updateElement();
		}
		var loading = $(".loading_wp");
		var myformData = new FormData(this);
		myformData.append('vnTKT', $.cookie('myTKT'));
		$.ajax({
			type: 'post',
			url: url_post,
			dataType : 'html',
			data: myformData,
			contentType: false,
			cache: false,
			processData: false,
			beforeSend: function(){
				loading.show();
			},
			complete: function(){
				loading.hide();
			},
			success: function(html){
				$("#result").html(html);
				$("#result").show();
			},
			error: function(){
				alert('Error please try again');
			}
		});
	});
	$(idform).submit(function(e){return false;});
}