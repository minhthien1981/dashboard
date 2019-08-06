function parallax() {
	var scrollPos = $(window).scrollTop(), speed = 0.5;
	$(".main_slider").css("top", (0 + (scrollPos * speed)) + 'px');
}
function RegisterPopup(){
	$('.login-page').show();
	$(".footerActionSubmit").click();
}
function LoginPopup(){
	$('.login-page').show();
}
function LoginPageClose(){
	$('.login-page').hide();
}
$(document).ready(function(){
	$(window).bind("scroll", function() {
	    parallax();
	});
	// Rating
	$('.basic').jRating();
	//Menu Fixed
	var trigger = $('.hamburger'),
	overlay = $('#overlay-hb'),
	isClosed = false;

	trigger.click(function (){
		hamburger_cross();    
	});
	overlay.click(function(){
		hamburger_cross();
	});
	function hamburger_cross(){
		if (isClosed == true){          
			overlay.hide();
			trigger.removeClass('is-open');
			trigger.addClass('is-closed');
			isClosed = false;
		} else {
			overlay.show();
			trigger.removeClass('is-closed');
			trigger.addClass('is-open');
			isClosed = true;
		}
		$('#wrapper').toggleClass('toggled');
		$('#navigation').toggleClass('toggled');
		$('#menuTopFixed').toggleClass('toggled');
	}
	$(document).on('click', '#menuTopFixed .drop-menu', function(){
		$(this).find('i.drop-menu-icon').toggleClass('active');
		$(this).next().slideToggle();
		$(this).blur();
		return false;
	});
	$('.owl-carousel-slider').owlCarousel({
		items:1,
		margin:0,
		autoplay:true,
		dots: true,
	    nav:true,
	    loop: true,
	    animateOut: 'fadeOut',
    	animateIn: 'fadeIn',   
	    transitionStyle:"fade",
	});
	jQuery('.owl-carousel-home').owlCarousel({
		dots: false,
	    nav:true,
	    thumbs: false,
    	thumbImage: false,
		thumbsPrerendered: false,
		responsive:{
            0:{
                items:3,
                margin:10,
            },
            600:{
                items:4,
                margin:10,
            },
            1000:{
                items:6,
                margin:30,
            }
        }
	});
	$("#mobile-menu").mobileMenu({
		MenuWidth: 250,
		SlideSpeed : 300,
		WindowsMaxWidth : 1024,
		PagePush : true,
		FromLeft : true,
		Overlay : true,
		CollapseMenu : true,
		ClassName : "mobile-menu"
	});

	
	var checkWidth = jQuery(document).width();  
  	if(checkWidth > 767){
		
	}else{
		var outw = jQuery( document ).width();
		var outh = (outw * 9) / 16;
		jQuery('#playerContainer.contentArea').css('height', outh);
		jQuery('.detailPlayer').css('height', (outh + 30));

		if($( "#myPlayer" ).length){
			Player.on(['waiting', 'pause'], function() {
				jQuery('#playerContainer').removeClass("fixed-play");
			});

			Player.on('playing', function() {
				jQuery('#playerContainer').addClass("fixed-play");
			});
		}
	}

	if(!!('ontouchstart' in window)){//check for touch device
		$('.searchIcon').unbind('click mouseenter mouseleave'); //use off if you used on, to unbind usual listeners
		$('.searchIcon').on('click',function(){
			jQuery('.searchField #searchTextFieldHome').css("width", "10px");
			jQuery('.searchField').show();
			jQuery(this).hide();
			jQuery('.searchField #searchTextFieldHome').animate({width: "200px"});
		});
	}
});

function ajax_load_data(url_post, url_direct, idform, idresult = '#result', classload = '.loading_wp') {
	$(idform).on('submit', function(e){
		var loading = $(classload);
		$.ajax({
			type: 'post',
			url: url_post,
			dataType : 'html',
			data: new FormData(this),
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
				if(html != ''){
					var data = jQuery.parseJSON(html);
					if(data['states'] == 'success'){
						$(idresult).html(data['messages']);
						$(idresult).show();
						setTimeout(function(){
							location.href = decodeURIComponent(url_direct);
						},1000);
						
					} else {
						$(idresult).html(data['messages']);
						$(idresult).show();
					}
				}				
			},
			error: function(){
				alert('Error please try again');
			}
		});
	});
	$(idform).submit(function(e){return false;});
}