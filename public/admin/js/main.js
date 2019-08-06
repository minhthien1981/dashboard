function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		var sect = $(input);
		reader.onload = function (e) {
			sect.parent().find('.this_img').attr('src', e.target.result);
			sect.parent().find('.this_img').height('auto').addClass('mgb10');
		}
		
		reader.readAsDataURL(input.files[0]);
	}
}
//Hàm check all
function CheckAll(class_name, obj) {
	var items = document.getElementsByClassName(class_name);
	if(obj.checked == true) {
		for (i = 0; i < items.length; i++)
		items[i].checked = true;
	} else {
		for (i = 0; i < items.length; i++)
		items[i].checked = false;
	}
}


//Ckfinder
function selectFileWithCKFinder( elementId, focus ) {
	CKFinder.modal( {
		chooseFiles: true,
		connectorPath: '/admin/ckfinder/connector',	
		width: 800,
		height: 600,
		top: 100,
		onInit: function( finder ) {
			finder.on( 'files:choose', function( evt ) {
				var file = evt.data.files.first();
				var output = document.getElementById( elementId );
				var imgfocus = document.getElementById( focus );
				output.value = file.getUrl();
				imgfocus.src = file.getUrl();
			} );

			finder.on( 'file:choose:resizedImage', function( evt ) {
				var output = document.getElementById( elementId );
				var imgfocus = document.getElementById( focus );
				output.value = evt.data.resizedUrl;
				imgfocus.src = evt.data.resizedUrl;
			} );
		}
	} );
}

$(document).ready(function(){
	// Loadpage effect
	$(window).load(function() { $("#spinner").fadeOut("slow"); });

	// Boostrap 4 Tooltip
	$('[data-toggle="tooltip"]').tooltip();

	//Ckfinder
	$(".btnCkfinder").click(function() {
		selectFileWithCKFinder($(this).attr('data-id'), $(this).attr("data-focus"));
	});
	

	//Order list
	$(".move-list").sortable();
	
	//Content Heading
	$(".arrow-up").hide();$(".hide-content").hide();
	$(document).on('click', '.option-heading' ,function(){
		$(this).next(".option-content").slideToggle(100);
		$(this).find(".arrow-up, .arrow-down").toggle();
	});

	//Close button message
	$(".closebtn").click(function() {$(this).parent().hide(500)});

	// Auto alias post
	$(".alias-in").keyup(function(){var alias = $(this).val();$(".alias-out").val(change_alias(alias));});


	// Change style input type file
	$(document).on('click', '.image_icon', function() { $(this).parent().find('.imgInp').click(); });
	$(document).on('change', '.imgInp', function(){ readURL(this); });
	
	// Auto description
	$("#metadescription").keyup(function(){
		var value = $(this).val();
		$("p.desc").text(value);
	});
	// Auto seo title
	$("#seotitle").keyup(function(){
		var value = $(this).val();
		$("p.title a").text(value);
	});
	// Tags
	$('.tags').tagsInput({
		width:'auto'
	});
	// Menu
	$(".myMenu h3").click(function() {
		$(".myMenu ul ul").slideUp();
		if (!$(this).next().is(":visible")) {
			$(this).next().slideDown()
		}
	});
	
	$(".tabs ul li").click(function() {
		$(".tabs ul li").removeClass('active');
		var current_index = $(this).index();
		$(".tabs ul li:eq("+current_index+")").addClass("active");
		$(".tabs section").hide();
		$(".tabs section:eq("+current_index+")").removeClass('hide').show();

	});

	var windowHeight = $(window).height();
	$(".contents").css("min-height", windowHeight);
	// Gotop site admin
	$(window).scroll(function(){if($(this).scrollTop() > 100)$('.copyright').addClass('copyrightActive');else $('.copyright').removeClass('copyrightActive');});
	$(window).scroll(function(){if ($(this).scrollTop() > 100)$('#goTop').addClass('goTopActive');else $('#goTop').removeClass('goTopActive');});$('#goTop').click(function(){$('body,html').animate({scrollTop: 0}, 'slow');});

	CKEDITOR.on( 'instanceReady', function( evt ) {
		evt.editor.dataProcessor.htmlFilter.addRules( {
			elements: {
	    		img: function(el) {
	    			el.addClass('img-responsive');
	    			el.attributes.data_src = el.attributes.src;
	    			el.attributes.src = '';
	    			//console.log(el);
	    			//el.SetAttribute('data-sd', 'x');
	    		}
			}
		});
	});
});